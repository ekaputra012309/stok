@extends('backend.template.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detail Barang Masuk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('barang_masuk.index') }}">Barang Masuk</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">No PO: {{ $barangMasuk->purchaseOrder->invoice_number ?? '-' }}</h3>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">No PO</th>
                                        <td>{{ $barangMasuk->purchaseOrder->invoice_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>User</th>
                                        <td>{{ $barangMasuk->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Barang Masuk</th>
                                        <td>{{ $barangMasuk->created_at->translatedFormat('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td>{{ $barangMasuk->note ?? '-' }}</td>
                                    </tr>
                                </table>

                                <h4 class="mt-4">Tracking Qty per Item</h4>

                                @foreach ($purchaseOrder->items as $poItem)
                                    @php
                                        $barangId = $poItem->barang->id;
                                        $hasValidDetail = false;
                                    @endphp

                                    {{-- Check if at least one detail for this barang has qty > 0 --}}
                                    @foreach ($allBarangMasuk as $bm)
                                        @php
                                            $detail = $bm->details->firstWhere('barang_id', $barangId);
                                        @endphp
                                        @if ($detail && $detail->qty > 0)
                                            @php
                                                $hasValidDetail = true;
                                                break;
                                            @endphp
                                        @endif
                                    @endforeach

                                    @if ($hasValidDetail)
                                        <span class="font-weight-bold" style="font-size: 1.2rem;">
                                            {{ $poItem->barang->deskripsi }} ({{ $poItem->barang->part_number }})
                                        </span>

                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-4" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Barang Masuk</th>
                                                        <th>Qty Masuk</th>
                                                        <th>Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $totalQty = 0; @endphp

                                                    @foreach ($allBarangMasuk as $bm)
                                                        @php
                                                            $detail = $bm->details->firstWhere('barang_id', $barangId);
                                                        @endphp
                                                        @if ($detail && $detail->qty > 0)
                                                            <tr>
                                                                <td>
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    {{ $bm->created_at->translatedFormat('d F Y,') }}
                                                                    <i class="fas fa-clock"></i>
                                                                    {{ $bm->created_at->translatedFormat('H:i') }}
                                                                </td>
                                                                <td>{{ $detail->qty }}</td>
                                                                <td>{{ $detail->barang->satuan->name ?? '-' }}</td>
                                                            </tr>
                                                            @php $totalQty += $detail->qty; @endphp
                                                        @endif
                                                    @endforeach

                                                    <tr>
                                                        <th>Total Qty Masuk</th>
                                                        <th>{{ $totalQty }}</th>
                                                        <th>{{ $poItem->barang->satuan->name ?? '-' }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Qty PO</th>
                                                        <th colspan="2">{{ $poItem->qty }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
