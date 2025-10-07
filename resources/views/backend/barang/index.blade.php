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
                        <h1>Barang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Barang</li>
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
                                <div class="card-title d-flex align-items-center w-100">
                                    <div class="row w-100">
                                        @if (in_array($role, ['superadmin', 'owner', 'admin']))
                                            <div class="col-md-6 d-flex align-items-center">
                                                <form action="{{ route('barang.import') }}" method="POST"
                                                    enctype="multipart/form-data" class="d-flex align-items-center w-100">
                                                    @csrf
                                                    <div class="form-group mb-0 mr-2 w-75">
                                                        <label for="file" class="sr-only">Import Excel</label>
                                                        <input type="file" name="file"
                                                            class="form-control form-control-sm" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success btn-sm ml-2">
                                                        <i class="fas fa-file-import"></i> Import
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-md-6 d-flex align-items-center">
                                                <span>&nbsp;</span>
                                            </div>
                                        @endif

                                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                                            <a href="{{ route('barang.template') }}" class="btn btn-info btn-sm mr-2">
                                                <i class="fas fa-download"></i> Template
                                            </a>
                                            <a href="{{ route('barang.export') }}" class="btn btn-success btn-sm mr-2">
                                                <i class="fas fa-upload"></i> Export
                                            </a>
                                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Add Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="card-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            @if (in_array($role, ['superadmin', 'owner', 'admin']))
                                                <th><input type="checkbox" id="select-all" class="select-all"></th>
                                                <!-- Checkbox to select all rows -->
                                            @endif
                                            <th>Part Number</th>
                                            <th>Deskripsi</th>
                                            <th>Lokasi Part</th>
                                            <th>Stok</th>
                                            <th>Limit</th>
                                            <th>Uom</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($databarang as $barang)
                                            <tr class="bg-{{ $barang->stok <= $barang->limit ? 'warning' : '' }}">

                                                @if (in_array($role, ['superadmin', 'owner', 'admin']))
                                                    <td>
                                                        <input type="checkbox" class="select-item"
                                                            data-id="{{ $barang->id }}">
                                                        &nbsp;

                                                        <a class="btn btn-xs btn-primary"
                                                            href="{{ route('barang.edit', $barang->id) }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <button class="btn btn-xs btn-danger delete-btn"
                                                            data-id="{{ $barang->id }}">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                @endif

                                                <td>{{ $barang->part_number }}</td>
                                                <td>{{ $barang->deskripsi }}</td>
                                                <td>{{ $barang->lokasi->nama_lokasi ?? '' }}</td>
                                                <td>{{ $barang->stok }}</td>
                                                <td>{{ $barang->limit }}</td>
                                                <td>{{ $barang->satuan->name ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (in_array($role, ['superadmin', 'owner', 'admin']))
                                <div class="card-footer">
                                    <button id="delete-selected" class="btn btn-danger btn-sm">Delete Selected</button>
                                    <span id="selected-count" class="ml-2">Selected: 0</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "columnDefs": [{
                        "targets": 0,
                        "checkboxes": {
                            "selectRow": true
                        }
                    }],
                    scrollY: "500px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: true,
                    "select": {
                        "style": "multi"
                    }
                });

                // Select all checkbox functionality
                $(document).on('change', 'input.select-all', function() {
                    var checked = $(this).prop('checked');
                    // Sync all header checkbox clones to the same state
                    $('input.select-all').prop('checked', checked);
                    // Toggle only the actual table tbody checkboxes (real rows)
                    $('#example1 tbody input.select-item').prop('checked', checked);
                    // Update counter
                    updateSelectedCount();
                });

                // Handle individual row checkbox selection
                $(document).on('change', '.select-item', function() {
                    var allChecked = $('.select-item:checked').length === $('.select-item').length;
                    $('#select-all').prop('checked', allChecked);
                });

                function updateSelectedCount() {
                    var selectedCount = $('.select-item:checked').length;
                    $('#selected-count').text('Selected: ' + selectedCount);
                }

                // Update count when checkboxes are changed
                $(document).on('change', '.select-item', function() {
                    updateSelectedCount();
                });

                // Handle delete selected rows
                $('#delete-selected').on('click', function() {
                    var selectedIds = [];
                    $('.select-item:checked').each(function() {
                        selectedIds.push($(this).data('id'));
                    });

                    if (selectedIds.length === 0) {
                        Swal.fire({
                            title: 'No rows selected',
                            text: 'Please select rows to delete.',
                            icon: 'warning',
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete selected!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete selected rows
                            $.ajax({
                                url: '{{ route('barang.destroy') }}', // Same route
                                // url: '{{ route('barang.destroy', ['barang' => 'delete-selected']) }}', // Use the same route for multiple deletion
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ids": selectedIds, // Send the selected IDs
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.success,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred while deleting.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });

            });
        </script>

        <script>
            $(document).on('click', '.delete-btn', function() {
                var barangId = $(this).data('id');
                var url = '{{ route('barang.destroy', ':id') }}';
                url = url.replace(':id', barangId); // Replace :id with the actual ID
                // console.log(url);
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
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.success,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        </script>

        <script>
            @if (session('errors'))
                let errorDetails = `<ul>`;
                @foreach (session('errors') as $error)
                    errorDetails += `<li>Row {{ $error['row'] }}: {!! $error['error'] !!}</li>`;
                @endforeach
                errorDetails += `</ul>`;

                Swal.fire({
                    title: 'Import Errors',
                    html: errorDetails, // Render HTML content (e.g., bold part numbers and satuan names)
                    icon: 'error',
                    width: '600px',
                    showConfirmButton: true,
                });
            @endif
        </script>
    </div>
@endsection
