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
            'tanggal_keluar' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $barangKeluar = BarangKeluar::create([
                'invoice_number' => $request->invoice_number,
                'po_number' => $request->po_number,
                'user_id' => auth()->user()->id,
                'customer_id' => $request->customer_id,
                'tanggal_keluar' => $request->tanggal_keluar,
            ]);
        
            foreach ($request->items as $templateId => $groupItems) {
                if (!is_array($groupItems)) continue;
        
                // Extract group total quantity
                $groupTotal = $groupItems['totalQty'] ?? 1;
        
                foreach ($groupItems as $index => $item) {
                    if ($index === 'totalQty') continue; // Skip the totalQty key itself
        
                    $barang = Barang::find($item['barang_id']);
                    if ($barang) {
                        // Decrement stock based on total quantity
                        $barang->decrement('stok', $item['qty'] );
                    }
        
                    BarangKeluarDetail::create([
                        'barang_keluar_id' => $barangKeluar->id,
                        'barang_id' => $item['barang_id'],
                        'qty' => $item['qty'] ,    // adjusted quantity
                        'total_group_qty' => $groupTotal,       // store the group total
                        'remarks' => $item['remarks'] ?? '',
                        'user_id' => $request->user_id,
                        'template_id' => $templateId,
                    ]);
                }
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
        $barang_template = BarangTemplate::with(['details.barang.satuan'])->get();

        // ✅ Use $BarangKeluar->id instead of undefined $id
        $details = BarangKeluarDetail::where('barang_keluar_id', $BarangKeluar->id)->get();

        // ✅ Group details by template_id and include group_total_qty
        $groupedDetails = $details->groupBy('template_id')->map(function ($items) {
            return [
                'total_group_qty' => $items->first()->total_group_qty ?? 1,
                'items' => $items,
            ];
        });

        $data = [
            'title' => 'Edit Barang Keluar | ',
            'barangKeluar' => $BarangKeluar->load('details.barang'),
            'barangs' => $barangs,
            'satuans' => $satuans,
            'customers' => $customers,
            'barang_template' => $barang_template,
            'groupedDetails' => $groupedDetails, // ✅ pass to view
        ];
        // dd($data);
        return view('backend.barang_keluar.edit', $data);
    }

    public function update(Request $request, BarangKeluar $BarangKeluar)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string|unique:barang_keluar,invoice_number,' . $BarangKeluar->id . '|max:255',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'totalQty' => 'nullable|integer',
            'items' => 'required|array',
            'items.*.*.barang_id' => 'required|exists:barang,id', // nested: template_id -> index -> barang_id
            'items.*.*.qty' => 'required|integer|min:1',
            'items.*.*.remarks' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
        ]);        

        DB::transaction(function () use ($request, $BarangKeluar) {
            // Update main BarangKeluar record
            $BarangKeluar->update([
                'user_id' => $request->user_id,
                'invoice_number' => $request->invoice_number,
                'po_number' => $request->po_number,
                'customer_id' => $request->customer_id,
                // 'totalQty' => $request->totalQty,
                'tanggal_keluar' => $request->tanggal_keluar,
            ]);
        
            // Revert stock for existing items and delete details
            foreach ($BarangKeluar->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->increment('stok', $detail->qty);
                }
                $detail->delete();
            }
        
            // Insert new items (grouped by template)
            foreach ($request->items as $templateId => $groupItems) {
                $groupTotalQty = $request->total_group_qty[$templateId] ?? 1;
    
                foreach ($groupItems as $item) {
                    $barang = Barang::find($item['barang_id']);
                    if ($barang) {
                        // Decrement stock based on total quantity
                        $barang->decrement('stok', $item['qty'] );
                    }

                    BarangKeluarDetail::create([
                        'barang_keluar_id' => $BarangKeluar->id,
                        'barang_id' => $item['barang_id'],
                        'qty' => $item['qty'],
                        'total_group_qty' => $groupTotalQty, // ✅ store group qty
                        'remarks' => $item['remarks'] ?? '',
                        'user_id' => auth()->id(),
                        'template_id' => $templateId,
                    ]);
                }
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

    // public function getBarangTemplateData($id)
    // {
    //     $template = BarangTemplate::with(['details.barang.satuan'])->find($id);
    //     // dd($template);
    //     if ($template) {
    //         return response()->json($template->details);
    //     }

    //     return response()->json([], 404); // Return empty response if template not found
    // }
    public function getBarangTemplateData($id)
    {
        $template = BarangTemplate::with(['details.barang.satuan'])->find($id);

        if ($template) {
            return response()->json([
                'nama_template' => $template->nama_template,
                'details' => $template->details,
            ]);
        }

        return response()->json([], 404);
    }

}
