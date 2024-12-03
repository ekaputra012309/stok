<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\BarangBroken;
use App\Models\BarangBrokenDetail;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $downloadType = $request->downloadType;

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // Convert start and end dates to the appropriate format
        $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();  // Set start of the day
        $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();  // Set end of the day (23:59:59)        

        if ($type == 'barang_masuk') {
            $judul = 'Barang Masuk';
            $transaksi = BarangMasuk::with(['details.barang', 'user', 'purchaseOrder'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } elseif ($type == 'barang_keluar') {
            $judul = 'Barang Keluar';
            $transaksi = BarangKeluar::with(['details.barang', 'user', 'customer'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } elseif ($type == 'barang_broken') {
            $judul = 'Barang Broken';
            $transaksi = BarangBroken::with(['details.barang', 'user'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } else {
            $judul = 'Transaksi';
            $transaksi = collect(); // Empty collection if no valid type is selected
        }

        if (!empty($partnumber)) {
            // Retrieve the `barang_id` matching the `part_number`
            $barangIds = Barang::where('part_number', 'like', '%' . $partnumber . '%')->pluck('id');
            
            if ($type == 'barang_masuk') {
                $transaksi = BarangMasukDetail::with('barang', 'barangMasuk.purchaseOrder');
            } elseif ($type == 'barang_keluar') {
                $transaksi = BarangKeluarDetail::with('barang', 'barangKeluar.customer');
            } elseif ($type == 'barang_broken') {
                $transaksi = BarangBrokenDetail::with('barang', 'barangbroken');
            } else {
                $transaksi = collect(); // Empty collection if no valid type is selected
            }
        
            if (!$transaksi instanceof \Illuminate\Support\Collection) { // Ensure we are working with a query builder
                $transaksi = $transaksi
                    ->whereIn('barang_id', $barangIds)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc');
            }
        }

        $data = [
            'title' => 'Laporan | ',
            'datatransaksi' =>  $transaksi->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'judul' => $judul,
            'type' => $type,
            'companyProfile' => CompanyProfile::first(),
        ];
        // dd($data['datatransaksi']);
        if ($downloadType === 'excel') {
            return Excel::download(
                new LaporanExport($startDate, $endDate, $type, $partnumber),
                'Laporan '. $judul . now()->format('Ymd_His') . '.xlsx'
            );
        } elseif ($downloadType === 'pdf') {
            // Generate PDF
            $view = empty($partnumber) ? 'backend.transaksi.print_laporan' : 'backend.transaksi.print_laporan1';
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data)->setPaper('A4', 'portrait');
            return $pdf->stream("Laporan-{$type}-{$startDate}-to-{$endDate}.pdf");
        }
    }
}
