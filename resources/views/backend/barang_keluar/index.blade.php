@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang Keluar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                        <li class="breadcrumb-item active">Barang Keluar</li>
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
                            <h3 class="card-title"> </h3>
                            <div class="card-tools">
                                <a href="{{ route('barang_template.create') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-plus"></i> Add Auto Build
                                </a>

                                <a href="{{ route('barang_keluar.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">Data Barang Keluar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Data Template Auto Build</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <table id="example1" class="table table-bordered table-striped w-100">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>No Surat Jalan</th>
                                                        <th>Customer</th>
                                                        <th>Stok Aktual</th>
                                                        <th>Part Number</th>
                                                        <th>Nama Barang</th>
                                                        <th>Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($databarang_keluar as $barangKeluar)
                                                        <tr>
                                                            <td>
                                                                <a class="btn btn-xs btn-dark" href="{{ route('barang_keluar.show', $barangKeluar->id) }}">
                                                                    <i class="fas fa-eye"></i> Show
                                                                </a> <br>
                                                                <a class="btn btn-xs btn-success" href="{{ route('barang_keluar.print', $barangKeluar->id) }}" target="_blank">
                                                                    <i class="fas fa-print"></i> Print Surat Jalan
                                                                </a> <br>
                                                                <a class="btn btn-xs btn-primary" href="{{ route('barang_keluar.edit', $barangKeluar->id) }}">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a> <br>  
                                                                <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $barangKeluar->id }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </td>
                                                            <td>{{ $barangKeluar->invoice_number }}</td>
                                                            <td>{{ $barangKeluar->customer->name ?? '' }}</td>
                                                            <td>
                                                                @foreach ($barangKeluar->details as $item)
                                                                    <strong>({{ $item->barang->stok }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach ($barangKeluar->details as $item)
                                                                    <strong>({{ $item->barang->part_number }})</strong> <br>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach ($barangKeluar->details as $item)
                                                                    {{ $item->barang->deskripsi }} <strong>({{ $item->qty }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $totalQty = $barangKeluar->details->sum('qty');
                                                                    echo $totalQty; // Total quantity of details
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <table id="example2" class="table table-bordered table-striped w-100">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama Template</th>
                                                        <th>Part Number</th>
                                                        <th>Nama Barang</th>
                                                        <th>Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($databarang_template as $barangTemplate)
                                                        <tr>
                                                            <td>
                                                                <!-- <a class="btn btn-xs btn-dark" href="{{ route('barang_template.show', $barangTemplate->id) }}">
                                                                    <i class="fas fa-eye"></i> Show
                                                                </a> <br> -->
                                                                <a class="btn btn-xs btn-success" href="{{ route('barang_template.print', $barangTemplate->id) }}" target="_blank">
                                                                    <i class="fas fa-print"></i> Print Template
                                                                </a> <br>
                                                                <a class="btn btn-xs btn-primary" href="{{ route('barang_template.edit', $barangTemplate->id) }}">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a> <br>  
                                                                <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $barangTemplate->id }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </td>
                                                            <td>{{ $barangTemplate->nama_template }}</td>
                                                            <td>
                                                                @foreach ($barangTemplate->details as $item)
                                                                    <strong>({{ $item->barang->part_number }})</strong> <br>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @foreach ($barangTemplate->details as $item)
                                                                    {{ $item->barang->deskripsi }} <strong>({{ $item->qty }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $totalQty = $barangTemplate->details->sum('qty');
                                                                    echo $totalQty; // Total quantity of details
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $("#example1").DataTable({
            // "responsive": true,
            // "lengthChange": true,
            // "autoWidth": false,
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   true,
        });

        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            // scrollY:        "300px",
            // scrollX:        true,
            // scrollCollapse: true,
            // paging:         false,
        });
    </script>
    
    <script>
        $(document).on('click', '.delete-btn', function() {
            var barangId = $(this).data('id');
            var url = '{{ route('barang_template.destroy', ':id') }}';
            url = url.replace(':id', barangId); // Replace :id with the actual ID
            
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