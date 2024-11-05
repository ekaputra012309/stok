<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\PurchaseOrder;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang Masuk | ',
            'approvedPurchaseOrders' => PurchaseOrder::where('status_order', 'Approved')
                ->whereNotIn('id', function($query) {
                    $query->select('purchase_order_id')
                        ->from('barang_masuk');
                })
                ->with(['items.barang.satuan'])
                ->orderBy('created_at', 'desc')
                ->get(),
            'databarang_masuk' => BarangMasuk::with(['purchaseOrder', 'details.barang.satuan'])
                ->orderBy('created_at', 'desc')
                ->get(),
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

        // Prepare data for the edit view
        $data = [
            'title' => 'Process Barang Masuk',
            'purchaseOrder' => $purchaseOrder,
            // Include any other data you might need in the view
        ];
        // dd($data);
        // Redirect to the edit view with the selected purchase order data
        return view('backend.barang_masuk.edit', $data);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'tanggal_masuk' => 'required|date', // Optional: if you want to validate the date
        ]);

        DB::transaction(function () use ($request) {
            // Create a new BarangMasuk entry
            $barangMasuk = BarangMasuk::create([
                'purchase_order_id' => $request->purchase_order_id,
                'user_id' => auth()->user()->id, // Assuming the user is authenticated
                'tanggal_masuk' => $request->tanggal_masuk, // Use the provided date
                'note' => $request->note,
            ]);

            // Loop through the items to create BarangMasukDetail entries and update stock
            foreach ($request->items as $item) {
                // Update stock in Barang table
                $barang = Barang::find($item['barang_id']);
                if ($barang) {
                    $barang->increment('stok', $item['qty']); // Increment stock by the quantity received
                }

                // Create a BarangMasukDetail entry
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'qty_verified' => true, // Or set based on your verification logic
                ]);
            }
        });

        // Show success alert
        Alert::success('Success', 'Barang Masuk transaction created successfully.')->autoClose(2000);
        
        // Redirect back to the index route
        return redirect()->route('barang_masuk.index');
    }

    public function show(BarangMasuk $BarangMasuk)
    {
        $data = [
            'title' => 'Detail Purchase Order | ',
            'BarangMasuk' => $BarangMasuk->load(['purchaseOrder', 'details.barang.satuan']),
        ];
        return view('backend.barang_masuk.show', $data);
    }
}
