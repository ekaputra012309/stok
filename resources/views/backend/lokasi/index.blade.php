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
                        <h1>Lokasi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Lokasi</li>
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
                                                <form action="{{ route('lokasi.import') }}" method="POST"
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
                                            <a href="{{ route('lokasi.template') }}" class="btn btn-info btn-sm mr-2">
                                                <i class="fas fa-download"></i> Template
                                            </a>
                                            <a href="{{ route('lokasi.export') }}" class="btn btn-success btn-sm mr-2">
                                                <i class="fas fa-upload"></i> Export
                                            </a>
                                            <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Add Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datalokasi as $lokasi)
                                            <tr>
                                                <td>
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('lokasi.edit', $lokasi->id) }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-xs btn-danger delete-btn"
                                                        data-id="{{ $lokasi->id }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                                <td>{{ $lokasi->nama_lokasi }}</td>
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
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                scrollY: "300px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: true,
            });
        </script>
        <script>
            $(document).on('click', '.delete-btn', function() {
                var lokasiId = $(this).data('id');
                var url = '{{ route('lokasi.destroy', ':id') }}';
                url = url.replace(':id', lokasiId); // Replace :id with the actual ID

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
    </div>
@endsection
