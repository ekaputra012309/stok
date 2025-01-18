@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                        <li class="breadcrumb-item active">Customer</li>
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
                                <a href="{{ route('customer.export') }}" class="btn btn-success btn-sm mr-2">
                                    <i class="fas fa-upload"></i> Export
                                </a>
                                <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>No Telp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datacustomer as $customer)
                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-primary" href="{{ route('customer.edit', $customer->id) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $customer->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->alamat }}</td>
                                        <td>{{ $customer->phone }}</td>
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
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   true,
        });
    </script>
    <script>
    $(document).on('click', '.delete-btn', function() {
        var customerId = $(this).data('id');
        var url = '{{ route('customer.destroy', ':id') }}';
        url = url.replace(':id', customerId); // Replace :id with the actual ID

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