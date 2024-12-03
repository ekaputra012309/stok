<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\BarangBroken;
use App\Models\BarangBrokenDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    protected $startDate;
    protected $endDate;
    protected $type_transaksi;
    protected $partnumber;

    public function __construct($startDate, $endDate, $type_transaksi, $partnumber = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type_transaksi = $type_transaksi;
        $this->partnumber = $partnumber;
    }

    public function view(): View
    {
        $transaksi = collect();

        if ($this->type_transaksi === 'barang_masuk') {
            $transaksi = BarangMasuk::with(['details.barang', 'user', 'purchaseOrder'])
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->get();
        } elseif ($this->type_transaksi === 'barang_keluar') {
            $transaksi = BarangKeluar::with(['details.barang', 'user', 'customer'])
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->get();
        } elseif ($this->type_transaksi === 'barang_broken') {
            $transaksi = BarangBroken::with(['details.barang', 'user'])
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->get();
        }

        if (!empty($this->partnumber)) {
            $barangIds = \App\Models\Barang::where('part_number', 'like', '%' . $this->partnumber . '%')->pluck('id');

            if ($this->type_transaksi === 'barang_masuk') {
                $transaksi = BarangMasukDetail::with('barang', 'barangMasuk.purchaseOrder')
                    ->whereIn('barang_id', $barangIds)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->get();
            } elseif ($this->type_transaksi === 'barang_keluar') {
                $transaksi = BarangKeluarDetail::with('barang', 'barangKeluar.customer')
                    ->whereIn('barang_id', $barangIds)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->get();
            } elseif ($this->type_transaksi === 'barang_broken') {
                $transaksi = BarangBrokenDetail::with('barang', 'barangbroken')
                    ->whereIn('barang_id', $barangIds)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->get();
            }
        }

        if ($this->type_transaksi == 'barang_masuk') {
            $judul = 'Barang Masuk';
        } elseif ($this->type_transaksi == 'barang_keluar') {
            $judul = 'Barang Keluar';
        } else {
            $judul = 'Barang Broken';
        }

        return view(!empty($this->partnumber) ? 'backend.transaksi.export_laporan1' : 'backend.transaksi.export_laporan', [
            'transaksi' => $transaksi,
            'type_transaksi' => $this->type_transaksi,
            'judul' => $judul,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}