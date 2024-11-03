<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Satuan;
use RealRashid\SweetAlert\Facades\Alert;

class SatuanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Satuan | ',
            'datasatuan' => Satuan::all(),
        ];
        $title = 'Delete Satuan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.satuan.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Satuan | ',
        ];
        return view('backend.satuan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        Satuan::create($request->all());
        Alert::success('Success', 'Satuan created successfully.');
        return redirect()->route('satuan.index');
    }

    public function show(Satuan $satuan)
    {
        $data = [
            'title' => 'View Satuan | ',
            'satuan' => $satuan,
        ];
        return view('backend.satuan.show', $data);
    }

    public function edit(Satuan $satuan)
    {
        $data = [
            'title' => 'Edit Satuan | ',
            'satuan' => $satuan,
        ];
        return view('backend.satuan.edit', $data);
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $satuan->update($request->all());
        Alert::success('Success', 'Satuan updated successfully.');

        return redirect()->route('satuan.index');
    }

    public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        Alert::success('Success', 'Satuan deleted successfully.');

        return redirect()->route('satuan.index');
    }    
}
