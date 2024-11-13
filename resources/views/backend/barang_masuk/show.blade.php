@extends('backend.template.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang Masuk Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang_masuk.index') }}">Barang Masuk</a></li>
                        <li class="breadcrumb-item active">Details</li>
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
                            <h3 class="card-title">Invoice: {{ $BarangMasuk->purchaseOrder->invoice_number }}</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Invoice Number</th>
                                    <td>{{ $BarangMasuk->purchaseOrder->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ $BarangMasuk->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Barang Masuk</th>
                                    <td>{{ $BarangMasuk->created_at->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $BarangMasuk->note }}</td>
                                </tr>
                            </table>

                            <h4 class="mt-4">Items</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($BarangMasuk->details as $item)
                                            <tr>
                                                <td><strong>({{ $item->barang->part_number }})</strong> {{ $item->barang->deskripsi }}</td>
                                                <td>{{ $item->qty .' '.$item->barang->satuan->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
