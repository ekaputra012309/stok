@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Barang Assy</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('barang_template.index') }}">Barang Assy</a></li>
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
                                <h3 class="card-title">Tambah Barang Assy</h3>
                            </div>

                            <form action="{{ route('barang_template.store') }}" method="POST" id="barang-masuk-form">
                                @csrf
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="nama_template">Part Number 1Assy</label>
                                            <input type="text"
                                                class="form-control @error('nama_template') is-invalid @enderror"
                                                id="nama_template" name="nama_template" placeholder="Part Number Assy"
                                                required>
                                            @error('nama_template')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="qty">Total Quantity</label>
                                            <input type="number"
                                                class="form-control qty-input @error('totalQty') is-invalid @enderror"
                                                name="totalQty" placeholder="Quantity">
                                            @error('totalQty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Container for Dynamic Barang Items -->
                                    <div id="items-container">
                                        <div class="item-row row">
                                            <div class="form-group col-md-2">
                                                <label for="barang_id">Barang</label>
                                                <select class="form-control select2bs4 barang-select"
                                                    name="items[0][barang_id]" required>
                                                    <option value="" data-deskripsi="" disabled selected>Select Part
                                                        Number</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}"
                                                            data-deskripsi="{{ $barang->deskripsi }}">
                                                            {{ $barang->part_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="deskripsi">Deskripsi</label>
                                                <input type="text" class="form-control deskripsi-input"
                                                    name="deskripsi_display" placeholder="Deskripsi" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="qty">Quantity</label>
                                                <input type="number"
                                                    class="form-control qty-input @error('items.0.qty') is-invalid @enderror"
                                                    name="items[0][qty]" placeholder="Quantity" required min="1">
                                                @error('items.0.qty')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-2 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-item">Remove</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" id="add-item" class="btn btn-success btn-sm">Add Another
                                        Item</button>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <a href="{{ route('barang_template.index') }}" class="btn btn-secondary">Back</a>
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

                $('#add-item').on('click', function() {
                    const newItemRow = `
                    <div class="item-row row">
                        <div class="form-group col-md-2">
                            <label for="barang_id">Barang</label>
                            <select class="form-control select2bs4 barang-select" name="items[${itemIndex}][barang_id]" required>
                                <option value="" data-deskripsi="" disabled selected>Select Part Number</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-deskripsi="{{ $barang->deskripsi }}">{{ $barang->part_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="deskripsi">Deskripsi</label>
                            <input type="text" class="form-control deskripsi-input" name="deskripsi_display" placeholder="Deskripsi" readonly>
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
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                    itemIndex++;
                    updateOptions();
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('.item-row').remove();
                    updateOptions();
                });

                $(document).on('change', '.barang-select', function() {
                    const deskripsi = $(this).find('option:selected').data('deskripsi');
                    $(this).closest('.item-row').find('.deskripsi-input').val(deskripsi);
                });
            });
        </script>

    </div>
@endsection
