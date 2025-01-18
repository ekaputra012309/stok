@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Barang Keluar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang_keluar.index') }}">Barang Keluar</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Barang Keluar</h3>
                        </div>

                        <form action="{{ route('barang_keluar.update', $barangKeluar->id) }}" method="POST" id="barang-masuk-form">
                            @csrf
                            @method('PUT')
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="po_number">No PO</label>
                                        <input type="text" class="form-control" id="po_number" name="po_number" placeholder="No Invoice" value="{{ $barangKeluar->po_number }}" readonly required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="invoice_number">No Invoice</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" placeholder="No Invoice" value="{{ $barangKeluar->invoice_number }}" readonly required>
                                    </div>

                                    <!-- Tanggal Keluar Field -->
                                    <div class="form-group col-md-3">
                                        <label for="tanggal_keluar">Tanggal Keluar Barang</label>
                                        <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="{{ $barangKeluar->tanggal_keluar }}">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="customer_id">Customer</label>
                                        <select class="form-control select2bs4" id="customer_id" name="customer_id" required>
                                            <option value="" disabled selected>Select Customer</option>
                                            @foreach ($customers as $customer_t)
                                                <option value="{{ $customer_t->id }}" {{ $barangKeluar->customer_id == $customer_t->id ? 'selected' : '' }}>
                                                    {{ $customer_t->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Container for Existing Items -->
                                <div id="items-container">
                                    @foreach ($barangKeluar->details as $index => $item)
                                        <div class="item-row row">
                                            <div class="form-group col-md-4">
                                                <label for="barang_id">Barang</label>
                                                <select class="form-control select2bs4" name="items[{{ $index }}][barang_id]" required>
                                                    <option value="" disabled>Select Barang</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" {{ $item->barang_id == $barang->id ? 'selected' : '' }}>
                                                            {{$barang->part_number}} {{ $barang->deskripsi . ' (' . $barang->stok . ' ' . $barang->satuan->name . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Stock</label>
                                                <input type="text" class="form-control" id="items[{{ $index }}][stok]" value="{{ $item->barang->stok }}" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="qty">Quantity</label>
                                                <input type="number" class="form-control qty-input" name="items[{{ $index }}][qty]" placeholder="Quantity" required min="1" value="{{ $item->qty }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" class="form-control remarks-input" name="items[{{ $index }}][remarks]" placeholder="Remarks" value="{{ $item->remarks }}">
                                            </div>
                                            <div class="form-group col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" id="add-item" class="btn btn-success btn-sm">Add Another Item</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Changes</button>
                                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            let itemIndex = {{ count($barangKeluar->details) }};

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
                        input.value = "";
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
                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">{{ $barang->deskripsi . ' (' . $barang->stok . ' ' . $barang->satuan->name . ')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Stock</label>
                            <input type="text" class="form-control" id="items[${itemIndex}][stok]" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control qty-input" name="items[${itemIndex}][qty]" placeholder="Quantity" required min="1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control remarks-input" name="items[${itemIndex}][remarks]" placeholder="Remarks" >
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
        });
    </script>

</div>
@endsection
