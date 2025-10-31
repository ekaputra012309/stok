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
                                        <div class="form-group col-md-3">
                                            <label for="po_number">No PO</label>
                                            <input type="text"
                                                class="form-control @error('po_number') is-invalid @enderror" id="po_number"
                                                name="po_number" placeholder="No PO" required>
                                            @error('po_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="invoice_number">No Surat Jalan</label>
                                            <input type="text"
                                                class="form-control @error('invoice_number') is-invalid @enderror"
                                                id="invoice_number" name="invoice_number" placeholder="No Surat Jalan"
                                                required>
                                            @error('invoice_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Tanggal Keluar Field -->
                                        <div class="form-group col-md-3">
                                            <label for="tanggal_keluar">Tanggal Keluar Barang</label>
                                            <input type="date" class="form-control" name="tanggal_keluar"
                                                id="tanggal_keluar" value="{{ now()->format('Y-m-d') }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="barang_template">Barang Assy</label>
                                            <select class="form-control select2bs4" id="barang_template" multiple>
                                                <option value="" disabled>Select Assy</option>
                                                @foreach ($barang_template as $barang_t)
                                                    <option value="{{ $barang_t->id }}">
                                                        {{ $barang_t->nama_template }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="customer_id">Customer</label>
                                            <select class="form-control select2bs4" id="customer_id" name="customer_id"
                                                required>
                                                <option value="" disabled selected>Select Customer</option>
                                                @foreach ($customers as $customer_t)
                                                    <option value="{{ $customer_t->id }}">
                                                        {{ $customer_t->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="totalQty">Total Quantity</label>
                                            <input type="number"
                                                class="form-control qty-input @error('totalQty') is-invalid @enderror"
                                                name="totalQty" placeholder="Quantity" required>
                                            @error('totalQty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Container for Dynamic Barang Items -->
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed"
                                                        type="button" data-toggle="collapse" data-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        Detail Items
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="accordion" id="assyAccordion">
                                                        <div id="assy-groups-container">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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

                // Add new item inside specific Assy group
                $(document).on('click', '.add-item', function() {
                    const templateId = $(this).data('template');
                    const $groupBody = $(`#assy-items-${templateId}`);
                    const index = $groupBody.find('.item-row').length;

                    const newItemRow = `
        <div class="item-row row">
            <div class="form-group col-md-2">
                <label>Barang</label>
                <select class="form-control select2bs4" name="items[${templateId}][${index}][barang_id]" required>
                    <option value="" disabled selected>Select Barang</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-deskripsi="{{ $barang->deskripsi }}">
                            {{ $barang->part_number }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Deskripsi</label>
                <input type="text" class="form-control" readonly>
            </div>
            <div class="form-group col-md-2">
                <label>Stock</label>
                <input type="text" class="form-control" readonly>
            </div>
            <div class="form-group col-md-2">
                <label>Quantity</label>
                <input type="number" class="form-control qty-input" name="items[${templateId}][${index}][qty]" placeholder="Quantity" required min="1">
            </div>
            <div class="form-group col-md-2">
                <label>Remarks</label>
                <input type="text" class="form-control" name="items[${templateId}][${index}][remarks]" placeholder="Remarks">
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            </div>
        </div>
    `;

                    $groupBody.append(newItemRow);
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('.item-row').remove();
                    updateOptions();
                });

                $(document).on('change', '[name^="items["][name$="[barang_id]"]', function() {
                    const stockValue = $(this).find(':selected').data('stok') || 0;
                    const deskripsiValue = $(this).find(':selected').data('deskripsi');
                    $(this).closest('.item-row').find('[id$="[stok]"]').val(stockValue);
                    $(this).closest('.item-row').find('[id$="[deskripsi]"]').val(deskripsiValue);
                    updateOptions();
                });

                $(document).on('input', '.qty-input', function() {
                    validateQty(this);
                });

                $(document).on('change', '#barang_template', function() {
                    const selectedTemplates = $(this).val() || []; // multiple IDs
                    const totalQtyValue = parseFloat($('[name="totalQty"]').val()) || 1;

                    // Remove groups for unselected Assy
                    $('#assy-groups-container .assy-group').each(function() {
                        const groupId = $(this).data('template-id').toString();
                        if (!selectedTemplates.includes(groupId)) {
                            $(this).remove();
                        }
                    });

                    // For each selected Assy, load if not already loaded
                    selectedTemplates.forEach(templateId => {
                        if ($(`#assy-group-${templateId}`).length) return; // already loaded

                        const url = barangTemplateUrl.replace(':id', templateId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(response) {
                                const details = response.details || [];
                                const assyName = `Barang Assy ${response.nama_template}` ??
                                    `Barang Assy ${templateId}`;

                                if (details.length === 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No Data Found',
                                        text: `No template items found for ${assyName}.`,
                                    });
                                    return;
                                }

                                const groupCard = `
                                    <div class="assy-group card mt-3" id="assy-group-${templateId}" data-template-id="${templateId}">
                                        <div class="card-header">
                                            <h3 class="card-title"> 
                                                <button class="btn btn-link text-left collapsed"
                                                        type="button"
                                                        data-toggle="collapse"
                                                        data-target="#collapse-${templateId}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-${templateId}">
                                                    🔹 ${assyName}
                                                </button>
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-assy ms-auto"
                                                    data-id="${templateId}">
                                                Remove Group
                                            </button>
                                            </div>
                                        </div>

                                        <div id="collapse-${templateId}"
                                            class="collapse"
                                            aria-labelledby="heading-${templateId}"
                                            data-parent="#assyAccordion">
                                            <div class="card-body" id="assy-items-${templateId}"></div>
                                        </div>

                                        <div id="collapse-${templateId}" class="collapse" aria-labelledby="heading-${templateId}" data-parent="#assyAccordion">
                                            <div class="card-body" id="assy-items-${templateId}">
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-success btn-sm add-item" data-template="${templateId}">
                                                    Add Another Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#assy-groups-container').append(groupCard);

                                const $groupBody = $(`#assy-items-${templateId}`);

                                details.forEach((item, index) => {
                                    const satuanName =
                                        item.barang && item.barang.satuan ?
                                        item.barang.satuan.name :
                                        'N/A';
                                    const calculatedQty = item.qty * totalQtyValue;

                                    const newItemRow = `
                                        <div class="item-row row">
                                            <div class="form-group col-md-2">
                                                <label>Barang</label>
                                                <select class="form-control select2bs4"
                                                        name="items[${templateId}][${index}][barang_id]" required>
                                                    <option value="${item.barang.id}" selected>
                                                        ${item.barang.part_number}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Deskripsi</label>
                                                <input type="text" class="form-control"
                                                    value="${item.barang.deskripsi}" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Stock</label>
                                                <input type="text" class="form-control"
                                                    value="${item.barang.stok}" readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Quantity</label>
                                                <input type="number"
                                                    class="form-control qty-input"
                                                    name="items[${templateId}][${index}][qty]"
                                                    value="${calculatedQty}"
                                                    data-original="${item.qty}"
                                                    placeholder="Quantity"
                                                    required min="1" max="${item.barang.stok}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Remarks</label>
                                                <input type="text"
                                                    class="form-control"
                                                    name="items[${templateId}][${index}][remarks]"
                                                    value="${item.remarks ?? ''}"
                                                    placeholder="Remarks">
                                            </div>
                                            <div class="form-group col-md-2 d-flex align-items-end">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm remove-item">Remove</button>
                                            </div>
                                        </div>
                                    `;
                                    $groupBody.append(newItemRow);
                                });

                                $('.select2bs4').select2({
                                    theme: 'bootstrap4'
                                });
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load template items.',
                                });
                            }
                        });
                    });
                });

                // Remove whole group
                $(document).on('click', '.remove-assy', function() {
                    const templateId = $(this).data('id');
                    $(`#assy-group-${templateId}`).remove();

                    // Deselect in Select2
                    const $select = $('#barang_template');
                    let values = $select.val() || [];
                    values = values.filter(v => v !== templateId.toString());
                    $select.val(values).trigger('change');
                });

                // Always store the original qty when the value changes (either manual or loaded)
                $(document).on('change input', '.qty-input', function() {
                    const val = parseFloat($(this).val()) || 0;
                    $(this).data('original', val);
                });

                // Multiply all item qty by totalQty multiplier
                $(document).on('input', '[name="totalQty"]', function() {
                    const multiplier = parseFloat($(this).val());

                    // If empty or zero, don't modify anything
                    if (isNaN(multiplier) || multiplier === 0) return;

                    $('#assy-groups-container .item-row').each(function() {
                        const qtyInput = $(this).find('.qty-input');
                        let originalQty = qtyInput.data('original');

                        // If not yet stored, use current value as original
                        if (originalQty === undefined) {
                            originalQty = parseFloat(qtyInput.val()) || 0;
                            qtyInput.data('original', originalQty);
                        }

                        const newQty = originalQty * multiplier;
                        qtyInput.val(newQty);
                    });
                });

            });
        </script>

    </div>
@endsection
