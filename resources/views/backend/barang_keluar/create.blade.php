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
                        <li class="breadcrumb-item"><a href="{{ route('barang_keluar.index') }}">Barang Keluar</a></li>
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
                            <h3 class="card-title">Tambah Barang Keluar</h3>
                        </div>

                        <form action="{{ route('barang_keluar.store') }}" method="POST" id="barang-masuk-form">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="invoice_number">No Invoice</label>
                                        <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" placeholder="No Invoice" required>
                                        @error('invoice_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Keluar Field -->
                                    <div class="form-group col-md-3">
                                        <label for="tanggal_keluar">Tanggal Keluar Barang</label>
                                        <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="{{ now()->format('Y-m-d') }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="barang_template">Barang Template</label>
                                        <select class="form-control select2bs4" id="barang_template">
                                            <option value="" disabled selected>Select Template</option>
                                            @foreach ($barang_template as $barang_t)
                                                <option value="{{ $barang_t->id }}">
                                                    {{ $barang_t->nama_template }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Container for Dynamic Barang Items -->
                                <div id="items-container">
                                    <div class="item-row row">
                                        <div class="form-group col-md-4">
                                            <label for="barang_id">Barang</label>
                                            <select class="form-control select2bs4" name="items[0][barang_id]" required>
                                                <option value="" disabled selected>Select Barang</option>
                                                @foreach ($barangs as $barang)
                                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">
                                                        <strong>{{ $barang->part_number }}</strong> {{ $barang->deskripsi . ' (' . $barang->stok . ' ' . $barang->satuan->name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Stock</label>
                                            <input type="text" class="form-control" id="items[0][stok]" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="qty">Quantity</label>
                                            <input type="number" class="form-control qty-input @error('items.0.qty') is-invalid @enderror" name="items[0][qty]" placeholder="Quantity" required min="1">
                                            @error('items.0.qty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-item" class="btn btn-success btn-sm">Add Another Item</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        const barangTemplateUrl = "{{ route('barang_template.get_data', ':id') }}";
    </script>

    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            let itemIndex = 1;

            function updateOptions() {
                let selectedBarang = [];
                $('[name^="items["][name$="[barang_id]"]').each(function() {
                    const value = $(this).val();
                    if (value) selectedBarang.push(value);
                });

                $('[name^="items["][name$="[barang_id]"]').each(function() {
                    $(this).find('option').each(function() {
                        if (selectedBarang.includes($(this).val()) && !$(this).is(':selected')) {
                            $(this).attr('disabled', 'disabled');
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });
                });
            }

            function validateQty(input) {
                const maxQty = $(input).closest('.item-row').find('[id$="[stok]"]').val();
                if (parseInt(input.value) > parseInt(maxQty)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Quantity',
                        text: 'Quantity cannot be greater than available stock.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Okay'
                    }).then(() => {
                        input.value = ""; // Clear the invalid input
                    });
                }
            }

            $('#add-item').on('click', function() {
                const newItemRow = `
                    <div class="item-row row">
                        <div class="form-group col-md-4">
                            <label for="barang_id">Barang</label>
                            <select class="form-control select2bs4" name="items[${itemIndex}][barang_id]" required>
                                <option value="" disabled selected>Select Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}"><strong>{{ $barang->part_number }}</strong> {{ $barang->deskripsi . ' (' . $barang->stok . ' ' . $barang->satuan->name . ')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Stock</label>
                            <input type="text" class="form-control" id="items[${itemIndex}][stok]" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control qty-input @error('items.${itemIndex}.qty') is-invalid @enderror" name="items[${itemIndex}][qty]" placeholder="Quantity" required min="1">
                            @error('items.${itemIndex}.qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </div>
                    </div>`;

                $('#items-container').append(newItemRow);
                $('.select2bs4').select2({ theme: 'bootstrap4' });
                itemIndex++;
                updateOptions();
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateOptions();
            });

            $(document).on('change', '[name^="items["][name$="[barang_id]"]', function() {
                const stockValue = $(this).find(':selected').data('stok') || 0;
                $(this).closest('.item-row').find('[id$="[stok]"]').val(stockValue);
                updateOptions();
            });

            $(document).on('input', '.qty-input', function() {
                validateQty(this);
            });

            $(document).on('change', '#barang_template', function() {
                const templateId = $(this).val(); // Get selected template ID
                
                if (templateId) {
                    // Replace :id with the actual template ID in the URL
                    const url = barangTemplateUrl.replace(':id', templateId);
                    
                    $.ajax({
                        url: url, // Dynamic URL for fetching template data
                        type: 'GET',
                        success: function(details) {
                            $('#items-container').empty(); // Clear current details if any
                            
                            if (details.length === 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No Data Found',
                                    text: 'No template items found for this template.',
                                });
                                return;
                            }

                            details.forEach((item, index) => {
                                const satuanName = item.barang && item.barang.satuan ? item.barang.satuan.name : 'N/A';
                                const newItemRow = `
                                    <div class="item-row row">
                                        <div class="form-group col-md-4">
                                            <label for="barang_id">Barang</label>
                                            <select class="form-control select2bs4" name="items[${index}][barang_id]" required>
                                                <option value="${item.barang.id}" selected>${item.barang.deskripsi} (${item.barang.stok} ${satuanName})</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Stock</label>
                                            <input type="text" class="form-control" id="items[${index}][stok]" value="${item.barang.stok}" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="qty">Quantity</label>
                                            <input type="number" class="form-control qty-input" name="items[${index}][qty]" value="${item.qty}" placeholder="Quantity" required min="1" max="${item.barang.stok}">
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                        </div>
                                    </div>`;
                                
                                $('#items-container').append(newItemRow); // Append new row
                            });

                            // Reinitialize select2 for the new items
                            $('.select2bs4').select2({ theme: 'bootstrap4' });
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText); // Log the error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to load template items.',
                            });
                        }
                    });
                }
            });

        });
    </script>

</div>
@endsection
