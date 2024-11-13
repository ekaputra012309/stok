@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Barang Broken</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang_broken.index') }}">Barang Broken</a></li>
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
                            <h3 class="card-title">Edit Barang Broken</h3>
                        </div>

                        <form action="{{ route('barang_broken.update', $barangBroken->id) }}" method="POST" id="barang-masuk-form">
                            @csrf
                            @method('PUT')
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="invoice_number">No Invoice</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" placeholder="No Invoice" value="{{ $barangBroken->invoice_number }}" readonly required>
                                    </div>

                                    <!-- Tanggal broken Field -->
                                    <div class="form-group col-md-3">
                                        <label for="tanggal_broken">Tanggal Broken Barang</label>
                                        <input type="date" class="form-control" name="tanggal_broken" id="tanggal_broken" value="{{ $barangBroken->tanggal_broken }}">
                                    </div>
                                </div>

                                <!-- Container for Existing Items -->
                                <div id="items-container">
                                    @foreach ($barangBroken->details as $index => $item)
                                        <div class="item-row row">
                                            <div class="form-group col-md-4">
                                                <label for="barang_id">Barang</label>
                                                <select class="form-control select2bs4" name="items[{{ $index }}][barang_id]" required>
                                                    <option value="" disabled>Select Barang</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" {{ $item->barang_id == $barang->id ? 'selected' : '' }}>
                                                            {{$barang->part_number}}  {{ $barang->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="qty">Quantity</label>
                                                <input type="number" class="form-control qty-input" name="items[{{ $index }}][qty]" placeholder="Quantity" required min="1" value="{{ $item->qty }}">
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
                                <a href="{{ route('barang_broken.index') }}" class="btn btn-secondary">Back</a>
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

            let itemIndex = {{ count($barangBroken->details) }};

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

            $('#add-item').on('click', function() {
                const newItemRow = `
                    <div class="item-row row">
                        <div class="form-group col-md-4">
                            <label for="barang_id">Barang</label>
                            <select class="form-control select2bs4" name="items[${itemIndex}][barang_id]" required>
                                <option value="" disabled selected>Select Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">{{ $barang->deskripsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control qty-input" name="items[${itemIndex}][qty]" placeholder="Quantity" required min="1">
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
        });
    </script>

</div>
@endsection
