<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $approvedPurchaseOrders = PurchaseOrder::where('status_order', 'Approved')
            ->whereNotIn('id', function ($query) {
                $query->select('purchase_order_id')
                    ->from('barang_masuk');
            })
            ->with(['items.barang.satuan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get barang_masuk records and group by purchase_order_id
        $groupedBarangMasuk = BarangMasuk::with(['purchaseOrder', 'details.barang.satuan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('purchase_order_id');

        $data = [
            'title' => 'Barang Masuk | ',
            'approvedPurchaseOrders' => $approvedPurchaseOrders,
            'groupedBarangMasuk' => $groupedBarangMasuk,
        ];

        return view('backend.barang_masuk.index', $data);
    }

    public function process(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_order,id',
        ]);

        // Retrieve the approved purchase order with its items
        $purchaseOrder = PurchaseOrder::with(['items.barang'])->findOrFail($request->purchase_order_id);

        $barangMasuk = BarangMasuk::with('details')
                        ->where('purchase_order_id', $request->purchase_order_id)
                        ->latest()
                        ->first();
        $existingDetails = optional($barangMasuk)->details ?? collect();
        $existingDetails = $existingDetails->keyBy('barang_id');
                        
        // Prepare data for the edit view
        $data = [
            'title' => 'Process Barang Masuk',
            'purchaseOrder' => $purchaseOrder,
            // Include any other data you might need in the view
            'barangMasuk' => $barangMasuk,
            'existingDetails' => $existingDetails,
        ];
        // dd($data);
        // Redirect to the edit view with the selected purchase order data
        return view('backend.barang_masuk.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:0',
            'items.*.qty_verified' => 'nullable|integer',
            'note' => 'nullable|string',
            'tanggal_masuk' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {            
                // Create new
                $barangMasuk = BarangMasuk::create([
                    'purchase_order_id' => $request->purchase_order_id,
                    'user_id' => auth()->id(),
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'note' => $request->note,
                ]);
            

            // Loop through and re-create details
            foreach ($request->items as $item) {
                $barangId = $item['barang_id'];
                $currentQty = (int) $item['qty'];
            
                // Total qty received so far
                $receivedQty = BarangMasukDetail::whereHas('barangMasuk', function ($query) use ($request) {
                    $query->where('purchase_order_id', $request->purchase_order_id);
                })->where('barang_id', $barangId)
                  ->sum('qty');
            
                // PO qty
                $poQty = PurchaseOrderItem::where('purchase_order_id', $request->purchase_order_id)
                          ->where('barang_id', $barangId)
                          ->value('qty');
            
                // Check if adding this qty would exceed PO
                if ($receivedQty + $currentQty > $poQty) {
                    throw ValidationException::withMessages([
                        "items.$barangId.qty" => "Jumlah barang masuk melebihi jumlah pada PO.",
                    ]);
                }
            
                // Process as usual
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $barangId,
                    'qty' => $currentQty,
                    'qty_verified' => isset($item['qty_verified']) ? 1 : 0,
                ]);

                // ✅ Update stok after detail created
                $barang = Barang::find($barangId);
                if ($barang) {
                    $barang->increment('stok', $currentQty);
                }
            }
            
        });

        Alert::success('Success', 'Barang Masuk successfully saved.')->autoClose(2000);
        return redirect()->route('barang_masuk.index');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        // Load related data
        $purchaseOrder = $barangMasuk->purchaseOrder()->with(['items.barang.satuan'])->first();

        // Get all BarangMasuk records for the same PO, ordered by date
        $allBarangMasuk = BarangMasuk::with(['details.barang.satuan'])
                            ->where('purchase_order_id', $purchaseOrder->id)
                            ->orderBy('created_at', 'asc')
                            ->get();

        $data = [
            'title' => 'Detail Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'purchaseOrder' => $purchaseOrder,
            'allBarangMasuk' => $allBarangMasuk,
        ];

        return view('backend.barang_masuk.show', $data);
    }
}
