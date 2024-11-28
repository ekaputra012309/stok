<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\BarangImport;
use App\Exports\BarangTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang | ',
            'databarang' => Barang::with('satuan')->get(),
        ];
        return view('backend.barang.index', $data);
    }

    public function create()
    {
        $satuans = Satuan::all(); // Get all satuans for dropdown
        $data = [
            'title' => 'Add Barang | ',
            'satuans' => $satuans,
        ];
        return view('backend.barang.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'part_number' => 'required|string|unique:barang,part_number|max:255',
            'satuan_id' => 'required|exists:satuan,id',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        Barang::create($request->all());
        Alert::success('Success', 'Barang created successfully.')->autoClose(2000);
        return redirect()->route('barang.index');
    }

    public function show(Barang $barang)
    {
        $data = [
            'title' => 'View Barang | ',
            'barang' => $barang,
        ];
        return view('backend.barang.show', $data);
    }

    public function edit(Barang $barang)
    {
        $satuans = Satuan::all(); // Get all satuans for dropdown
        $data = [
            'title' => 'Edit Barang | ',
            'barang' => $barang,
            'satuans' => $satuans,
        ];
        return view('backend.barang.edit', $data);
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'part_number' => 'required|string|max:255|unique:barang,part_number,' . $barang->id,
            'satuan_id' => 'required|exists:satuan,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $barang->update($request->all());
        Alert::success('Success', 'Barang updated successfully.')->autoClose(2000);

        return redirect()->route('barang.index');
    }

    public function destroy(Request $request, Barang $barang = null)
    {
        // $barang->delete();
        // return response()->json(['success' => 'Barang deleted successfully.']);
        if ($request->has('ids') && is_array($request->input('ids'))) {
            // If there is an array of IDs, delete the selected records
            Barang::whereIn('id', $request->input('ids'))->delete();
            return response()->json(['success' => 'Selected barang records deleted successfully.']);
        }
    
        if ($barang) {
            // If there's a single Barang model, delete it
            $barang->delete();
            return response()->json(['success' => 'Barang deleted successfully.']);
        }
    
        return response()->json(['error' => 'No items selected'], 400);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new BarangImport();
        Excel::import($import, $request->file('file'));

        // dd($import->errors);

        if (count($import->errors) > 0) {
            // Pass formatted errors to the view
            return redirect()->route('barang.index')->with([
                'success' => 'Barang imported with some errors.',
                'errors' => $import->errors, // Pass errors to the view
            ]);
        }

        Alert::success('Success', 'Barang imported successfully.')->autoClose(2000);
        return redirect()->route('barang.index');
    }

    public function downloadTemplate()
    {
        return Excel::download(new BarangTemplateExport, 'barang_template.xlsx');
    }
}
