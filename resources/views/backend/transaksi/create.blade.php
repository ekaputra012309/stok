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
                        <li class="breadcrumb-item"><a href="{{ route('privilage.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Add</li>
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
                            <h3 class="card-title">Add Transaction</h3>
                        </div>
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth                            

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="mb-4">Transaction Details</h3>
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
                                        <div id="totalAmount" class="border p-3">
                                            Rp <span id="total">0.00</span>
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