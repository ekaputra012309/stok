@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Barang Broken</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('barang_broken.index') }}">Barang Broken</a></li>
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
                                <h3 class="card-title">Tambah Barang Broken</h3>
                            </div>

                            <form action="{{ route('barang_broken.store') }}" method="POST" id="barang-masuk-form">
                                @csrf
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="invoice_number">No Invoice</label>
                                            <input type="text"
                                                class="form-control @error('invoice_number') is-invalid @enderror"
                                                id="invoice_number" name="invoice_number" placeholder="No Invoice" required>
                                            @error('invoice_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Tanggal broken Field -->
                                        <div class="form-group col-md-3">
                                            <label for="tanggal_broken">Tanggal Broken Barang</label>
                                            <input type="date" class="form-control" name="tanggal_broken"
                                                id="tanggal_broken" value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <!-- Table Container for Dynamic Barang Items -->
                                    <div class="card mt-3">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h3 class="card-title m-0">Detail Items</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped mb-0"
                                                    style="min-width: 1028px;">
                                                    <thead>
                                                        <tr>
                                                            <th>Barang</th>
                                                            <th>Deskripsi</th>
                                                            <th style="width: 100px;">Stok</th>
                                                            <th style="width: 100px;">Quantity</th>
                                                            <th style="width: 200px;">Remarks</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="items-container">
                                                        <tr class="item-row">
                                                            <td>
                                                                <select class="form-control select2bs4 barang-select"
                                                                    name="items[0][barang_id]" required>
                                                                    <option value="" data-deskripsi="" disabled
                                                                        selected>Select Part Number</option>
                                                                    @foreach ($barangs as $barang)
                                                                        <option value="{{ $barang->id }}"
                                                                            data-deskripsi="{{ $barang->deskripsi }}"
                                                                            data-stok="{{ $barang->stok }}">
                                                                            {{ $barang->part_number }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control deskripsi-input"
                                                                    name="deskripsi_display" placeholder="Deskripsi"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control stok-input"
                                                                    name="stok_display" placeholder="stok" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control qty-input @error('items.0.qty') is-invalid @enderror"
                                                                    name="items[0][qty]" placeholder="Quantity" required
                                                                    min="1">
                                                                @error('items.0.qty')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control remarks-input @error('items.0.remarks') is-invalid @enderror"
                                                                    name="items[0][remarks]" placeholder="Remarks">
                                                                @error('items.0.remarks')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-item">Remove</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" id="add-item" class="btn btn-success btn-sm">Add
                                                Another Item</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
                    <tr class="item-row">
                        <td >
                            <select class="form-control select2bs4 barang-select" name="items[${itemIndex}][barang_id]" required>
                                <option value="" data-deskripsi="" disabled selected>Select Part Number</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-deskripsi="{{ $barang->deskripsi }}" data-stok="{{ $barang->stok }}">{{ $barang->part_number }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td >
                            <input type="text" class="form-control deskripsi-input" name="deskripsi_display" placeholder="Deskripsi" readonly>
                        </td>
                        <td >
                            <input type="text" class="form-control stok-input" name="stok_display" placeholder="stok" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control qty-input @error('items.${itemIndex}.qty') is-invalid @enderror" name="items[${itemIndex}][qty]" placeholder="Quantity" required min="1">
                        </td>
                        <td>
                            <input type="text" class="form-control remarks-input @error('items.${itemIndex}.remarks') is-invalid @enderror" name="items[${itemIndex}][remarks]" placeholder="Remarks" >
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </td>
                    </tr>`;

                    $('#items-container').append(newItemRow);
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                    itemIndex++;
                    updateOptions();
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr.item-row').remove();
                    updateOptions();
                });

                $(document).on('change', '.barang-select', function() {
                    const deskripsi = $(this).find('option:selected').data('deskripsi');
                    const stok = $(this).find('option:selected').data('stok');
                    $(this).closest('tr.item-row').find('.deskripsi-input').val(deskripsi);
                    $(this).closest('tr.item-row').find('.stok-input').val(stok);
                });
            });
        </script>

    </div>
@endsection
