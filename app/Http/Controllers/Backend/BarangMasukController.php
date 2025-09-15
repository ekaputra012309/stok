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
            'items.*.qty' => 'required|integer|min:1',
            'items.*.qty_verified' => 'nullable|integer',
            'note' => 'nullable|string',
            'tanggal_masuk' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            // Check if a BarangMasuk already exists for the same purchase_order_id
            $barangMasuk = BarangMasuk::where('purchase_order_id', $request->purchase_order_id)->first();

            if ($barangMasuk) {
                // Update existing record
                $barangMasuk->update([
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'note' => $request->note,
                    'user_id' => auth()->id(),
                ]);

                // Delete previous details to avoid duplication
                $barangMasuk->details()->delete();
            } else {
                // Create new
                $barangMasuk = BarangMasuk::create([
                    'purchase_order_id' => $request->purchase_order_id,
                    'user_id' => auth()->id(),
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'note' => $request->note,
                ]);
            }

            // Loop through and re-create details
            foreach ($request->items as $item) {
                $qtyVerified = isset($item['qty_verified']) ? (int)$item['qty_verified'] : 0;

                // Update stock
                $barang = Barang::find($item['barang_id']);
                if ($barang) {
                    $barang->increment('stok', $item['qty']);
                }

                // Create detail
                BarangMasukDetail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'qty_verified' => $qtyVerified,
                ]);
            }
        });

        Alert::success('Success', 'Barang Masuk successfully saved.')->autoClose(2000);
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
