<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\CompanyProfile;
use App\Models\BarangTemplate;
use App\Models\BarangTemplateDetail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class BarangTemplateController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang Template | ',
            'databarang_template' => BarangTemplate::with(['details.barang.satuan'])->orderBy('created_at', 'desc')->get(),
        ];
        return view('backend.barang_template.index', $data);
    }

    public function create()
    {
        $barangs = Barang::all();
        $data = [
            'title' => 'Add Barang Template | ',
            'barangs' => $barangs,
        ];
        return view('backend.barang_template.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'totalQty' => 'nullable|integer',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);        

        DB::transaction(function () use ($request) {
            // Create a new BarangTemplate entry
            $BarangTemplate = BarangTemplate::create([
                'nama_template' => $request->nama_template,
                'totalQty' => $request->totalQty,
                'user_id' => auth()->user()->id, // Assuming the user is authenticated
            ]);

            // Loop through the items to create BarangTemplateDetail entries and update stock
            foreach ($request->items as $item) {
                // Create a BarangTemplateDetail entry
                BarangTemplateDetail::create([
                    'barang_template_id' => $BarangTemplate->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'user_id' => $request->user_id,
                ]);
            }
        });
        
        Alert::success('Success', 'Barang Template transaction created successfully.')->autoClose(2000);
        return redirect()->route('barang_template.index');
    }

    public function show(BarangTemplate $BarangTemplate)
    {
        $data = [
            'title' => 'Detail Barang Template | ',
            'BarangTemplate' => $BarangTemplate->load('details.barang'),
        ];
        return view('backend.barang_template.show', $data);
    }

    public function edit(BarangTemplate $BarangTemplate)
    {
        $barangs = Barang::all();
        $satuans = Satuan::all();
        $data = [
            'title' => 'Edit Barang Template | ',
            'barangTemplate' => $BarangTemplate->load('details.barang'),
            'barangs' => $barangs,
            'satuans' => $satuans,
        ];
        return view('backend.barang_template.edit', $data);
    }

    public function update(Request $request, BarangTemplate $BarangTemplate)
    {
        // dd($request->all());
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'totalQty' => 'nullable|integer',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $BarangTemplate) {
            // Update the BarangTemplate entry
            $BarangTemplate->update([
                'user_id' => $request->user_id,
                'nama_template' => $request->nama_template,
                'totalQty' => $request->totalQty,
            ]);

            // Revert stock changes for existing items
            foreach ($BarangTemplate->details as $item) {
                $item->delete(); // Delete old detail
            }

            // Insert updated items and decrement stock accordingly
            foreach ($request->items as $item) {
                // Create new BarangTemplateDetail entry
                BarangTemplateDetail::create([
                    'barang_template_id' => $BarangTemplate->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'user_id' => $request->user_id,
                ]);
            }
        });

        Alert::success('Success', 'Barang Template updated successfully.')->autoClose(2000);
        return redirect()->route('barang_template.index');
    }

    public function destroy(BarangTemplate $BarangTemplate)
    {
        DB::transaction(function () use ($BarangTemplate) {
            // Delete related BarangTemplateDetail entries
            $BarangTemplate->details()->delete();

            // Finally, delete the BarangTemplate entry
            $BarangTemplate->delete();
        });

        return response()->json(['success' => 'Barang Template transaction deleted successfully.']);
    }

    public function print($id)
    {
        $BarangTemplate = BarangTemplate::with('details.barang', 'user')->findOrFail($id);
        $companyProfile = CompanyProfile::first();

        $data = [
            'title' => 'Print Barang Template | ',
            'barangTemplate' => $BarangTemplate,
            'companyProfile' => $companyProfile,
        ];

        $pdf = FacadePdf::loadView('backend.barang_template.print_barang_template', $data);
        // Clean the file name to avoid invalid characters
        $safeFileName = Str::slug($BarangTemplate->nama_template, '_') . '.pdf';
        // Example: "HOSE ASSY HOSE REEL 1-1/2"" → "hose_assy_hose_reel_1-1_2.pdf"
        return $pdf->stream($safeFileName);
    }
}
