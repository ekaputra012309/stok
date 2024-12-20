<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangLimit;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class BarangLimitController extends Controller
{
    public function create()
    {
        $excludedBarangIds = BarangLimit::where('status', '=', 1)->pluck('barang_id');
        $barangs = Barang::whereNotIn('id', $excludedBarangIds)->get();
        $data = [
            'title' => 'Add Barang Limit | ',
            'barangs' => $barangs,
        ];
        return view('backend.barang_limit.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'barang_id' => 'required|exists:barang,id',
            'qtyLimit' => 'required|integer|min:1',            
        ]);        

        BarangLimit::create([
            'barang_id' => $request->barang_id,
            'qtyLimit' => $request->qtyLimit,
            'user_id' => $request->user_id,
        ]);
        
        Alert::success('Success', 'Barang limit created successfully.')->autoClose(2000);
        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        // dd($id);
        $baranglimit = BarangLimit::find($id);
        $baranglimit->update(['status' => 0]);
        return response()->json(['success' => 'Barang limit transaction deleted successfully.']);
    }
}
