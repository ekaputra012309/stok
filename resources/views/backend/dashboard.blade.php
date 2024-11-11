@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp

@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- You can add breadcrumb links here if needed -->
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $barang }}</h3>

                        <p>Item Barang</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ $role !== 'superadmin' || $role !== 'admin' ? route('barang.index') : route('dashboard') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $barang_masuk }}</h3>

                        <p>Barang Masuk Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('barang_masuk.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $barang_keluar }}</h3>

                        <p>Barang Keluar Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('barang_keluar.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $barang_broken }}</h3>

                        <p>Barang Broken Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('barang_broken.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="@if ($role == 'superadmin' || $role == 'owner') col-md-6 @else col-md-12 @endif col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <span class="text-danger font-italic font-weight-bold">* Data barang dengan stok <= 5 *</span>
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('purchase_order.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Buat Purchase Order (PO)
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <th>Stok</th>
                                        <th>Uom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($databarang as $barang)
                                    <tr>
                                        <td>{{ $barang->deskripsi }}</td>
                                        <td>{{ $barang->stok }}</td>
                                        <td>{{ $barang->satuan->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if (in_array($role, ['superadmin', 'owner']))
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <span class="text-danger font-italic font-weight-bold">* Data PO belum proses!! *</span>
                            </h3>
                            <div class="card-tools">
                                
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datapurchase_order as $purchaseOrder)
                                        <tr>
                                            <td>
                                                <a class="btn btn-xs btn-dark" href="{{ route('purchase_order.show', $purchaseOrder->id) }}">
                                                    <i class="fas fa-eye"></i> Proses
                                                </a> <br>
                                            </td>
                                            <td>{{ $purchaseOrder->created_at->translatedFormat('d F Y') }}</td>
                                            <td>
                                                @foreach ($purchaseOrder->items as $item)
                                                    {{ $item->barang->deskripsi }} <strong>({{ $item->qty }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @php
                                                    $totalQty = $purchaseOrder->items->sum('qty');
                                                    echo $totalQty; // Total quantity of items
                                                @endphp
                                            </td>
                                            <td>
                                                <span class="btn btn-sm 
                                                    @switch($purchaseOrder->status_order)
                                                        @case('Rejected')
                                                            btn-warning
                                                            @break
                                                        @case('Approved')
                                                            btn-success
                                                            @break
                                                        @default
                                                            btn-secondary
                                                    @endswitch
                                                ">
                                                    {{ $purchaseOrder->status_order ?: 'Belum Diproses' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>        
        </div>
    </section>
    <script>
        $("#example1, #example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "pageLength": 5,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    </script>
</div>
@endsection
