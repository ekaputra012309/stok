@extends('backend/template/app')

@section('content')
    <style>
        /* Ensure the table respects explicit widths inside Bootstrap 4 wrapper */
        table.dataTable {
            width: 100% !important;
        }

        /* Optional: force minimum column widths to trigger scrollX properly */
        #example1 th,
        #example1 td {
            white-space: nowrap;
        }
    </style>
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
                                    <a href="{{ route('barang_keluar.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add Data
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Surat Jalan</th>
                                            <th>Customer</th>
                                            <th>Stok Aktual</th>
                                            <th>Part Number</th>
                                            <th>Nama Barang</th>
                                            <th>Remarks</th>
                                            <th>Total Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($databarang_keluar as $barangKeluar)
                                            <tr>
                                                <td>
                                                    <a class="btn btn-xs btn-dark"
                                                        href="{{ route('barang_keluar.show', $barangKeluar->id) }}">
                                                        <i class="fas fa-eye"></i> Show
                                                    </a> <br>
                                                    <a class="btn btn-xs btn-success"
                                                        href="{{ route('barang_keluar.print', $barangKeluar->id) }}"
                                                        target="_blank">
                                                        <i class="fas fa-print"></i> Print Surat Jalan
                                                    </a> <br>
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('barang_keluar.edit', $barangKeluar->id) }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a> <br>
                                                    <button class="btn btn-xs btn-danger delete-btn"
                                                        data-id="{{ $barangKeluar->id }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                                <td>
                                                    {{ $barangKeluar->invoice_number }} <br>
                                                    @if ($barangKeluar->po_number)
                                                        No PO :
                                                        <strong>{{ $barangKeluar->po_number }}</strong>
                                                    @else
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $barangKeluar->customer->name ?? '' }}
                                                </td>
                                                <td>
                                                    @foreach ($barangKeluar->details as $item)
                                                        <strong>({{ number_format($item->barang->stok, 0, ',', '.') }}
                                                            @if ($item->barang->satuan)
                                                                {{ $item->barang->satuan->name }}
                                                            @endif)
                                                        </strong><br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($barangKeluar->details as $item)
                                                        <strong>({{ $item->barang->part_number }})</strong>
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($barangKeluar->details as $item)
                                                        {{ $item->barang->deskripsi }}
                                                        <strong>({{ number_format($item->qty, 0, ',', '.') }} @if ($item->barang->satuan)
                                                                {{ $item->barang->satuan->name }}
                                                            @endif)</strong><br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($barangKeluar->details as $item)
                                                        {{ $item->remarks }} <br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @php
                                                        $qtyBySatuan = $barangKeluar->details->groupBy(
                                                            fn($item) => $item->barang->satuan->name ?? 'Tanpa Satuan',
                                                        );
                                                    @endphp

                                                    @foreach ($qtyBySatuan as $satuan => $items)
                                                        <strong>
                                                            {{ number_format($items->sum('qty'), 0, ',', '.') }}
                                                            {{ $satuan }}
                                                        </strong>
                                                        <br>
                                                    @endforeach
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
        </section>
        <script>
            var table = $("#example1").DataTable({
                scrollY: "500px",
                scrollX: true,
                scrollCollapse: true,
                // paging: false,
                // autoWidth: false,
                fixedColumns: true,

                // columnDefs: [{
                //         targets: 0,
                //         width: "100px"
                //     },
                //     {
                //         targets: 1,
                //         width: "120px"
                //     },
                //     {
                //         targets: 2,
                //         width: "120px"
                //     },
                //     {
                //         targets: 6,
                //         width: "80px"
                //     }
                // ],

                // initComplete: function() {
                //     this.api().columns.adjust().draw(false);
                // }
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
                var url = '{{ route('barang_keluar.destroy', ':id') }}';
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
                                var errorMessage = xhr.responseJSON?.message ||
                                    'An error occurred while deleting.';
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
