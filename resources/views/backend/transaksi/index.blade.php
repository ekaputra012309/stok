@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                        <li class="breadcrumb-item active">Transaksi</li>
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
                            <h3 class="card-title">Tambah Transaksi</h3>
                        </div>
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth                            

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="mb-4">Transaksi Detail</h4>
                                        <div id="details">
                                            <div class="form-row mb-3">
                                                <div class="col">
                                                    <label for="qty">Kuantiti:</label>
                                                    <input type="number" class="form-control qty" name="details[0][qty]" required>
                                                </div>
                                                <div class="col">
                                                    <label for="harga">Harga:</label>
                                                    <input type="text" class="form-control price" name="details[0][harga]" required>
                                                </div>
                                                <input type="hidden" name="details[0][satuan]" value="Kg">
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-secondary" id="addDetail">Add Detail</button>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <h3 class="mb-4">Total Bayar</h3>
                                        <div id="totalAmount" class="p-3">
                                            Rp <br> <span id="total" style="font-size: 40pt">0.00</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <!-- <div class="card-header">
                            <h3 class="card-title"> </h3>
                            <div class="card-tools">
                                <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                            </div>
                        </div> -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Invoice</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datatransaksi as $transaksi)
                                    <tr>
                                        <td>
                                            <!-- <a class="btn btn-sm btn-primary" href="{{ route('transaksi.edit', $transaksi->id) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a> -->
                                            <a class="btn btn-sm btn-danger" href="{{ route('transaksi.destroy', $transaksi->id) }}" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                        <td>{{ $transaksi->no_inv }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y, H:i') }}</td>
                                        <td>Rp {{ number_format($transaksi->total, 2, ',', '.') }}</td>
                                        <td>{{ $transaksi->user->name }}</td>
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
        $(document).ready(function() {
            let detailIndex = 1; // Start from 1 since we already have one detail

            // Function to calculate total amount
            function calculateTotal() {
                let total = 0;
                $('.form-row').each(function() {
                    const qty = $(this).find('.qty').val() || 0;
                    const price = parseInt($(this).find('.price').data('rawValue') || 0); // Get raw value for calculation
                    total += qty * price;
                });
                $('#total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })); // Update total amount
            }

            // Function to format price input
            function formatPriceInput(input) {
                const rawValue = input.val().replace(/\./g, ''); // Remove dots for raw value
                const formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format for display
                input.val(formattedValue); // Update input with formatted value
                input.data('rawValue', rawValue); // Store the raw value in data attribute
            }

            // Add initial event listener for the first detail
            $('.qty, .price').on('input', function() {
                if ($(this).hasClass('price')) {
                    formatPriceInput($(this));
                }
                calculateTotal();
            });

            $('#addDetail').on('click', function() {
                const newDetail = `
                    <div class="form-row mb-3">
                        <div class="col">
                            <label for="qty">Kuantiti:</label>
                            <input type="number" class="form-control qty" name="details[${detailIndex}][qty]" required>
                        </div>
                        <div class="col">
                            <label for="harga">Harga:</label>
                            <input type="text" class="form-control price" name="details[${detailIndex}][harga]" required>
                        </div>
                        <input type="hidden" name="details[${detailIndex}][satuan]" value="Kg">
                    </div>`;
                $('#details').append(newDetail);
                detailIndex++; // Increment the index for the next detail

                // Add event listener for new fields
                $('.qty:last, .price:last').on('input', function() {
                    if ($(this).hasClass('price')) {
                        formatPriceInput($(this));
                    }
                    calculateTotal();
                });

                calculateTotal(); // Recalculate total
            });

            // Form submission handler
            $('form').on('submit', function() {
                $('.price').each(function() {
                    const rawValue = $(this).data('rawValue');
                    $(this).val(rawValue); // Set the input value to the raw value for submission
                });
            });
        });
    </script>
</div>
@endsection