<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;
use RealRashid\SweetAlert\Facades\Alert;

class BarangController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Barang | ',
            'databarang' => Barang::with('satuan')->get(),
        ];
        $title = 'Delete Barang!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
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
        Alert::success('Success', 'Barang created successfully.');
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
        Alert::success('Success', 'Barang updated successfully.');

        return redirect()->route('barang.index');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        Alert::success('Success', 'Barang deleted successfully.');

        return redirect()->route('barang.index');
    }    
}
