@extends('backend.template.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang Keluar Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang_keluar.index') }}">Barang Keluar</a></li>
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
                            <h3 class="card-title">Invoice: {{ $BarangKeluar->invoice_number }}</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Invoice Number</th>
                                    <td>{{ $BarangKeluar->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ $BarangKeluar->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Barang Keluar</th>
                                    <td>{{ $BarangKeluar->created_at->translatedFormat('d F Y') }}</td>
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
                                        @foreach ($BarangKeluar->details as $item)
                                            <tr>
                                                <td>{{ $item->barang->deskripsi }}</td>
                                                <td>{{ $item->qty .' '.$item->barang->satuan->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right">Grand Total</th>
                                            <th>{{ number_format($BarangKeluar->details->sum(fn($item) => $item->qty ), 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>                            
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
