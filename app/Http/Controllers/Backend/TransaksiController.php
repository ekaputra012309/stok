<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangBroken;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class TransaksiController extends Controller
{
    public function laporan()
    {   
        $data = array(
            'title' => 'Laporan | ',
        );        
        return view('backend.transaksi.laporan', $data);
    }

    public function cetakLaporan(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type_transaksi;
        $partnumber = $request->partnumber;

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // Convert start and end dates to the appropriate format
        $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();  // Set start of the day
        $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();  // Set end of the day (23:59:59)

        if (!empty($partnumber)) {
            // Retrieve the `barang_id` matching the `part_number`
            $barangIds = Barang::where('part_number', 'like', '%' . $partnumber . '%')->pluck('id');
        }
        
        if ($type == 'barang_masuk') {
            $judul = 'Barang Masuk';
            $transaksi = BarangMasuk::with(['details.barang', 'user', 'purchaseOrder'])
                ->whereHas('details', function ($query) use ($barangIds) {
                    if (!empty($barangIds)) {
                        $query->whereIn('barang_id', $barangIds);
                    }
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } elseif ($type == 'barang_keluar') {
            $judul = 'Barang Keluar';
            $transaksi = BarangKeluar::with(['details.barang', 'user'])
                ->whereHas('details', function ($query) use ($barangIds) {
                    if (!empty($barangIds)) {
                        $query->whereIn('barang_id', $barangIds);
                    }
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } elseif ($type == 'barang_broken') {
            $judul = 'Barang Broken';
            $transaksi = BarangBroken::with(['details.barang', 'user'])
                ->whereHas('details', function ($query) use ($barangIds) {
                    if (!empty($barangIds)) {
                        $query->whereIn('barang_id', $barangIds);
                    }
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } else {
            $judul = 'Transaksi';
            $transaksi = collect(); // Empty collection if no valid type is selected
        }
        
        $companyProfile = CompanyProfile::first();

        $data = [
            'title' => 'Laporan | ',
            'datatransaksi' =>  $transaksi->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'judul' => $judul,
            'type' => $type,
            'companyProfile' => $companyProfile,
        ];
        // dd($data['datatransaksi']);
        $pdf = FacadePdf::loadView('backend.transaksi.print_laporan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Laporan-'.$judul.'-'.$startDate.'-'.$endDate.'.pdf');
    }
}
