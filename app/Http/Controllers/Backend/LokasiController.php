<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\LokasiExport;
use Maatwebsite\Excel\Facades\Excel;

class LokasiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Lokasi | ',
            'datalokasi' => Lokasi::all(),
        ];
        return view('backend.lokasi.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Lokasi | ',
        ];
        return view('backend.lokasi.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Split input by comma, trim spaces
        $lokasiList = array_map('trim', explode(',', $request->nama_lokasi));

        foreach ($lokasiList as $nama) {
            Lokasi::create([
                'nama_lokasi' => $nama,
                'user_id' => $request->user_id,
            ]);
        }

        Alert::success('Success', 'Lokasi created successfully.')->autoClose(2000);
        return redirect()->route('lokasi.index');
    }

    public function show(Lokasi $lokasi)
    {
        $data = [
            'title' => 'View Lokasi | ',
            'lokasi' => $lokasi,
        ];
        return view('backend.lokasi.show', $data);
    }

    public function edit(Lokasi $lokasi)
    {
        $data = [
            'title' => 'Edit Lokasi | ',
            'lokasi' => $lokasi,
        ];
        return view('backend.lokasi.edit', $data);
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $lokasi->update($request->all());
        Alert::success('Success', 'Lokasi updated successfully.')->autoClose(2000);

        return redirect()->route('lokasi.index');
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return response()->json(['success' => 'Lokasi deleted successfully.']);
        // Alert::success('Success', 'lokasi deleted successfully.');

        // return redirect()->route('lokasi.index');
    }

    public function export()
    {
        $fileName = 'lokasi-' . date('Ymd') . '.xlsx';
        return Excel::download(new LokasiExport, $fileName);
    }
}
