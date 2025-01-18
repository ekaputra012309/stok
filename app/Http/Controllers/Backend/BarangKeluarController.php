<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Customer;
use App\Models\CompanyProfile;
use App\Models\BarangKeluar;
use App\Models\BarangTemplate;
use App\Models\BarangKeluarDetail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang Keluar | ',
            'databarang_keluar' => BarangKeluar::with(['details.barang.satuan', 'customer'])->orderBy('created_at', 'desc')->get(),
            'databarang_template' => BarangTemplate::with(['details.barang.satuan'])->orderBy('created_at', 'desc')->get(),
        ];
        return view('backend.barang_keluar.index', $data);
    }

    private function generateInvoiceNumber($userId, $datePrefix)
    {
        // Include "BK" in the matching pattern to avoid mismatches
        $latestInvoice = BarangKeluar::where('user_id', $userId)
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
        $poNumber = $this->generateInvoiceNumber($userId, $datePrefix);
        $barangs = Barang::with('satuan')->where('stok', '>', 0)->get();
        $barang_template = BarangTemplate::with(['details.barang.satuan'])->get();
        $customers = Customer::all();
        $data = [
            'title' => 'Add Barang Keluar | ',
            'barangs' => $barangs,
            'customers' => $customers,
            'barang_template' => $barang_template,
            'poNumber' => $poNumber,
        ];
        return view('backend.barang_keluar.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:barang_keluar,invoice_number|max:255',
            'po_number' => 'required|string|unique:barang_keluar,po_number|max:255',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
        ]);        

        DB::transaction(function () use ($request) {
            // Create a new BarangKeluar entry
            $barangKeluar = BarangKeluar::create([
                'invoice_number' => $request->invoice_number,
                'po_number' => $request->po_number,
                'user_id' => auth()->user()->id, // Assuming the user is authenticated
                'customer_id' => $request->customer_id,
                'tanggal_keluar' => $request->tanggal_keluar, // Use the provided date
            ]);

            // Loop through the items to create BarangKeluarDetail entries and update stock
            foreach ($request->items as $item) {
                // Update stock in Barang table
                $barang = Barang::find($item['barang_id']);
                if ($barang) {
                    $barang->decrement('stok', $item['qty']); // Increment stock by the quantity received
                }

                // Create a BarangKeluarDetail entry
                BarangKeluarDetail::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'remarks' => $item['remarks'] ?? '',
                    'user_id' => $request->user_id,
                ]);
            }
        });
        
        Alert::success('Success', 'Barang Keluar transaction created successfully.')->autoClose(2000);
        return redirect()->route('barang_keluar.index');
    }

    public function show(BarangKeluar $BarangKeluar)
    {
        $data = [
            'title' => 'Detail Barang Keluar | ',
            'BarangKeluar' => $BarangKeluar->load('details.barang'),
        ];
        return view('backend.barang_keluar.show', $data);
    }

    public function edit(BarangKeluar $BarangKeluar)
    {
        $barangs = Barang::with('satuan')->where('stok', '>', 0)->get();
        $satuans = Satuan::all();
        $customers = Customer::all();
        $data = [
            'title' => 'Edit Barang Keluar | ',
            'barangKeluar' => $BarangKeluar->load('details.barang'),
            'barangs' => $barangs,
            'satuans' => $satuans,
            'customers' => $customers,
        ];
        return view('backend.barang_keluar.edit', $data);
    }

    public function update(Request $request, BarangKeluar $BarangKeluar)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:barang_keluar,invoice_number,' . $BarangKeluar->id . '|max:255',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $BarangKeluar) {
            // Update the BarangKeluar entry
            $BarangKeluar->update([
                'user_id' => $request->user_id,
                'invoice_number' => $request->invoice_number,
                'po_number' => $request->po_number,
                'customer_id' => $request->customer_id,
                'tanggal_keluar' => $request->tanggal_keluar,
            ]);

            // Revert stock changes for existing items
            foreach ($BarangKeluar->details as $item) {
                $barang = Barang::find($item->barang_id);
                if ($barang) {
                    $barang->increment('stok', $item->qty); // Add back previous stock amount
                }
                $item->delete(); // Delete old detail
            }

            // Insert updated items and decrement stock accordingly
            foreach ($request->items as $item) {
                $barang = Barang::find($item['barang_id']);
                if ($barang) {
                    $barang->decrement('stok', $item['qty']); // Reduce stock by the updated qty
                }

                // Create new BarangKeluarDetail entry
                BarangKeluarDetail::create([
                    'barang_keluar_id' => $BarangKeluar->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'remarks' => $item['remarks'] ?? '',
                    'user_id' => $request->user_id,
                ]);
            }
        });

        Alert::success('Success', 'Barang Keluar updated successfully.')->autoClose(2000);
        return redirect()->route('barang_keluar.index');
    }

    public function destroy(BarangKeluar $BarangKeluar)
    {
        DB::transaction(function () use ($BarangKeluar) {
            // Loop through details and update stock in Barang
            foreach ($BarangKeluar->details as $item) {
                // Find the Barang record and decrement the stock if it exists
                $barang = Barang::find($item->barang_id);
                if ($barang) {
                    $barang->increment('stok', $item->qty); // Restore the stock quantity
                }
            }

            // Delete related BarangKeluarDetail entries
            $BarangKeluar->details()->delete();

            // Finally, delete the BarangKeluar entry
            $BarangKeluar->delete();
        });

        return response()->json(['success' => 'Barang Keluar transaction deleted successfully.']);
    }


    public function print($id)
    {
        $barangKeluar = BarangKeluar::with('details.barang', 'user', 'customer')->findOrFail($id); // Fetch the Barang Keluar
        $companyProfile = CompanyProfile::first(); // Retrieve the company profile
        $data = [
            'title' => 'Print Barang Keluar | ',
            'barangKeluar' => $barangKeluar,
            'companyProfile' => $companyProfile,
        ];
        // dd($data);
        $pdf = FacadePdf::loadView('backend.barang_keluar.print_barang_keluar', $data);
        // $pdf->setPaper('A4', 'portrait'); // Set paper size
        return $pdf->stream(''.$barangKeluar->invoice_number.'.pdf'); // Stream the PDF
    }

    public function getBarangTemplateData($id)
    {
        $template = BarangTemplate::with(['details.barang.satuan'])->find($id);
        // dd($template);
        if ($template) {
            return response()->json($template->details);
        }

        return response()->json([], 404); // Return empty response if template not found
    }
}
