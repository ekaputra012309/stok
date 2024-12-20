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
                <div class="@if ($role == 'superadmin' || $role == 'owner') col-md-12 @else col-md-12 @endif col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <span class="text-danger font-italic font-weight-bold">* Data barang dengan stok limit *</span>
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('barang_limit.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah barang limit
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Deskripsi</th>
                                        <th>Stok sistem</th>
                                        <th>Stok limit</th>
                                        <th>Uom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($databarang as $brg)
                                    <tr>
                                        <td>
                                        <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $brg->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        </td>
                                        <td><strong>({{$brg->barang->part_number}})</strong> {{ $brg->barang->deskripsi }}</td>
                                        <td>{{ $brg->barang->stok }}</td>
                                        <td>{{ $brg->qtyLimit }}</td>
                                        <td>{{ $brg->barang->satuan->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if (in_array($role, ['superadmin', 'owner']))
                <div class="col-md-12 col-12">
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
                                                    <strong>({{$item->barang->part_number}})</strong> {{ $item->barang->deskripsi }} <strong>({{ $item->qty }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
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
        });
    </script>
    <script>
        $(document).on('click', '.delete-btn', function() {
            var barangId = $(this).data('id');
            var url = '{{ route('barang_limit.destroy', ':id') }}';
            url = url.replace(':id', barangId); // Replace :id with the actual ID
            console.log(barangId);

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE', // Set the HTTP method to DELETE
                        data: {
                            "_token": "{{ csrf_token() }}" // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                timer: 2000, // Close after 2 seconds
                                showConfirmButton: false, // No OK button
                                timerProgressBar: true // Show progress bar
                            }).then(() => {
                                location.reload(); // Reload the page or update the UI
                            });
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON?.message || 'An error occurred while deleting.';
                            Swal.fire(
                                'Error!',
                                errorMessage,
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
</div>
@endsection
