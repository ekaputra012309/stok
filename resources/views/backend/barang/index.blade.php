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
                            <h3 class="card-title"> </h3>
                            <div class="card-tools">
                                <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Part Number</th>
                                        <th>Deskripsi</th>
                                        <th>Stok</th>
                                        <th>Uom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($databarang as $barang)
                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-primary" href="{{ route('barang.edit', $barang->id) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $barang->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                        <td>{{ $barang->part_number }}</td>
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
            </div>
        </div>
    </section>
    <script>
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    </script>
    <script>
    $(document).on('click', '.delete-btn', function() {
        var barangId = $(this).data('id');
        var url = '{{ route('barang.destroy', ':id') }}';
        url = url.replace(':id', barangId); // Replace :id with the actual ID

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