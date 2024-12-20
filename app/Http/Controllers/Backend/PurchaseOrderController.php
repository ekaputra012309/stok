<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\CompanyProfile;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Purchase Order | ',
            'datapurchase_order' => PurchaseOrder::with(['items.barang.satuan'])->orderBy('created_at', 'desc')->get(),
        ];
        return view('backend.purchase_order.index', $data);
    }

    private function generateInvoiceNumber($userId, $datePrefix)
    {
        // Include "PO" in the matching pattern to avoid mismatches
        $latestInvoice = PurchaseOrder::where('user_id', $userId)
            ->where('invoice_number', 'like', "PO{$datePrefix}{$userId}%")
            ->latest('invoice_number')
            ->first();

        if ($latestInvoice) {
            // Correctly extract the last 3 digits for consistent incrementation
            $lastSequence = (int) substr($latestInvoice->invoice_number, -3);
            $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '001';
        }

        return "PO{$datePrefix}{$userId}{$newSequence}";
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $datePrefix = Carbon::now()->format('ymd'); // Format: YYMMDD
        $invoiceNumber = $this->generateInvoiceNumber($userId, $datePrefix);
        $barangs = Barang::all(); // Get all barang for selection
        $satuans = Satuan::all();
        $data = [
            'title' => 'Add Purchase Order | ',
            'barangs' => $barangs,
            'satuans' => $satuans,
            'invoiceNumber' => $invoiceNumber,
        ];
        return view('backend.purchase_order.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_order,invoice_number|max:255',
            'vendor' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Create PurchaseOrder transaction
            $PurchaseOrder = PurchaseOrder::create([
                'user_id' => $request->user_id,
                'invoice_number' => $request->invoice_number,
                'vendor' => $request->vendor,
            ]);

            // Loop through items to create PurchaseOrderItem entries and update stock
            foreach ($request->items as $item) {
                $barangItem = Barang::find($item['barang_id']);
                
                // Update stock in Barang table
                // $barangItem->increment('stok', $item['qty']);
                
                // Create a PurchaseOrderItem entry
                PurchaseOrderItem::create([
                    'purchase_order_id' => $PurchaseOrder->id,
                    'barang_id' => $item['barang_id'],
                    'user_id' => $request->user_id,
                    'qty' => $item['qty'],
                ]);
            }
        });

        Alert::success('Success', 'Purchase Order transaction created successfully.')->autoClose(2000);
        return redirect()->route('purchase_order.index');
    }

    public function show(PurchaseOrder $PurchaseOrder)
    {
        $data = [
            'title' => 'Detail Purchase Order | ',
            'PurchaseOrder' => $PurchaseOrder->load(['items.barang.satuan']),
        ];
        return view('backend.purchase_order.show', $data);
    }

    public function approve(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
    
        // Validate input
        $request->validate([
            'status_order' => 'required|in:Approved,Rejected',
            'note' => 'required_if:status_order,Rejected|string|max:255',
        ]);
    
        // Update Purchase Order status and other fields
        $purchaseOrder->status_order = $request->status_order;
        $purchaseOrder->approveby = auth()->user()->id;
    
        if ($request->status_order === 'Rejected') {
            $purchaseOrder->note = $request->note; // Set the note for rejection
        } else {
            $purchaseOrder->note = 'Approved'; // Set the note to "Approved" if approved
        }
    
        $purchaseOrder->save();
    
        Alert::success('Success', 'Purchase Order status updated successfully.')->autoClose(2000);
    
        return redirect()->route('purchase_order.index');
    }  

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $barangs = Barang::all(); // Get all items for selection
        $satuans = Satuan::all();
        $data = [
            'title' => 'Edit Purchase Order | ',
            'purchaseOrder' => $purchaseOrder->load('items.barang'),
            'barangs' => $barangs,
            'satuans' => $satuans,
        ];
        return view('backend.purchase_order.edit', $data);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_order,invoice_number,' . $purchaseOrder->id . '|max:255',
            'vendor' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            // Update the PurchaseOrder
            $purchaseOrder->update([
                'user_id' => $request->user_id,
                'invoice_number' => $request->invoice_number,
                'vendor' => $request->vendor,
            ]);

            // Remove old items and update stock
            foreach ($purchaseOrder->items as $item) {
                // Revert the stock update on `Barang` table
                $barang = Barang::find($item->barang_id);
                // $barang->decrement('stok', $item->qty);
                $item->delete();
            }

            // Add updated items and adjust stock
            foreach ($request->items as $item) {
                // $barangItem = Barang::find($item['barang_id']);
                // $barangItem->increment('stok', $item['qty']); // Update stock

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'barang_id' => $item['barang_id'],
                    'user_id' => $request->user_id,
                    'qty' => $item['qty'],
                ]);
            }
        });

        Alert::success('Success', 'Purchase Order updated successfully.')->autoClose(2000);
        return redirect()->route('purchase_order.index');
    }

    public function destroy(PurchaseOrder $PurchaseOrder)
    {
        DB::transaction(function () use ($PurchaseOrder) {
            // Loop through items and update stock in Barang
            // foreach ($PurchaseOrder->items as $item) {
            //     // Find the Barang record and decrement the stock
            //     $barang = Barang::find($item->barang_id);
            //     $barang->decrement('stok', $item->qty);
            // }

            // Delete related PurchaseOrderItem entries
            $PurchaseOrder->items()->delete();

            // Finally, delete the PurchaseOrder entry
            $PurchaseOrder->delete();
        });

        return response()->json(['success' => 'Purchase Order transaction deleted successfully.']);
    }

    public function print($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.barang', 'user')->findOrFail($id); // Fetch the purchase order
        $companyProfile = CompanyProfile::first(); // Retrieve the company profile
        $pdf = FacadePdf::loadView('backend.purchase_order.print_purchase_order', compact('purchaseOrder', 'companyProfile'));
        // $pdf->setPaper('A4', 'portrait'); // Set paper size
        return $pdf->stream(''.$purchaseOrder->invoice_number.'.pdf'); // Stream the PDF
    }
}
