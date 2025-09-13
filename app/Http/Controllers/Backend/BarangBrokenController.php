<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\CompanyProfile;
use App\Models\BarangBroken;
use App\Models\BarangBrokenDetail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class BarangBrokenController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang Broken | ',
            'databarang_broken' => BarangBroken::with(['details.barang.satuan'])->orderBy('created_at', 'desc')->get(),
        ];
        return view('backend.barang_broken.index', $data);
    }

    private function generateInvoiceNumber($userId, $datePrefix)
    {
        // Include "BKN" in the matching pattern to avoid mismatches
        $latestInvoice = BarangBroken::where('user_id', $userId)
            ->where('invoice_number', 'like', "BKN{$datePrefix}{$userId}%")
            ->latest('invoice_number')
            ->first();

        if ($latestInvoice) {
            // Correctly extract the last 3 digits for consistent incrementation
            $lastSequence = (int) substr($latestInvoice->invoice_number, -3);
            $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '001';
        }

        return "BKN{$datePrefix}{$userId}{$newSequence}";
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $datePrefix = Carbon::now()->format('ymd'); // Format: YYMMDD
        $invoiceNumber = $this->generateInvoiceNumber($userId, $datePrefix);
        $barangs = Barang::all();
        $data = [
            'title' => 'Add Barang Broken | ',
            'barangs' => $barangs,
            'invoiceNumber' => $invoiceNumber,
        ];
        return view('backend.barang_broken.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:barang_broken,invoice_number|max:255',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string|max:255',
            'tanggal_broken' => 'required|date',
        ]);        

        DB::transaction(function () use ($request) {
            // Create a new BarangBroken entry
            $barangBroken = BarangBroken::create([
                'invoice_number' => $request->invoice_number,
                'user_id' => auth()->user()->id, // Assuming the user is authenticated
                'tanggal_broken' => $request->tanggal_broken, // Use the provided date
                'note' => $request->note,
            ]);

            // Loop through the items to create BarangBrokenDetail entries and update stock
            foreach ($request->items as $item) {
                // Create a BarangBrokenDetail entry
                BarangBrokenDetail::create([
                    'barang_broken_id' => $barangBroken->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'remarks' => $item['remarks'],
                    'user_id' => $request->user_id,
                ]);
            }
        });
        
        Alert::success('Success', 'Barang Broken transaction created successfully.')->autoClose(2000);
        return redirect()->route('barang_broken.index');
    }

    public function show(BarangBroken $barangBroken)
    {
        $data = [
            'title' => 'Detail Barang Broken | ',
            'barangBroken' => $barangBroken->load('details.barang'),
        ];
        return view('backend.barang_broken.show', $data);
    }

    public function edit(BarangBroken $barangBroken)
    {
        $barangs = Barang::all();
        $satuans = Satuan::all();
        $data = [
            'title' => 'Edit Barang Broken | ',
            'barangBroken' => $barangBroken->load('details.barang'),
            'barangs' => $barangs,
            'satuans' => $satuans,
        ];
        return view('backend.barang_broken.edit', $data);
    }

    public function update(Request $request, BarangBroken $barangBroken)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:barang_broken,invoice_number,' . $barangBroken->id . '|max:255',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string|max:255',
            'tanggal_broken' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $barangBroken) {
            // Update the BarangBroken entry
            $barangBroken->update([
                'user_id' => $request->user_id,
                'invoice_number' => $request->invoice_number,
                'tanggal_broken' => $request->tanggal_broken,
            ]);

            // Revert stock changes for existing items
            foreach ($barangBroken->details as $item) {
                $item->delete(); // Delete old detail
            }

            // Insert updated items and decrement stock accordingly
            foreach ($request->items as $item) {
                // Create new BarangBrokenDetail entry
                BarangBrokenDetail::create([
                    'barang_broken_id' => $barangBroken->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'remarks' => $item['remarks'],
                    'user_id' => $request->user_id,
                ]);
            }
        });

        Alert::success('Success', 'Barang Broken updated successfully.')->autoClose(2000);
        return redirect()->route('barang_broken.index');
    }

    public function destroy(BarangBroken $barangBroken)
    {
        DB::transaction(function () use ($barangBroken) {
            // Delete related BarangBrokenDetail entries
            $barangBroken->details()->delete();

            // Finally, delete the BarangBroken entry
            $barangBroken->delete();
        });

        return response()->json(['success' => 'Barang Broken transaction deleted successfully.']);
    }

    public function print($id)
    {
        $barangBroken = BarangBroken::with('details.barang', 'user')->findOrFail($id); // Fetch the Barang Broken
        $companyProfile = CompanyProfile::first(); // Retrieve the company profile
        $data = [
            'title' => 'Print Barang Broken | ',
            'barangBroken' => $barangBroken,
            'companyProfile' => $companyProfile,
        ];
        // dd($data);
        $pdf = FacadePdf::loadView('backend.barang_broken.print_barang_broken', $data);
        return $pdf->stream(''.$barangBroken->invoice_number.'.pdf'); // Stream the PDF
    }
}
