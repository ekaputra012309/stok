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

                            <form action="{{ route('barang_keluar.update', $barangKeluar->id) }}" method="POST"
                                id="barang-masuk-form">
                                @csrf
                                @method('PUT')
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="po_number">No PO</label>
                                            <input type="text" class="form-control" id="po_number" name="po_number"
                                                value="{{ $barangKeluar->po_number }}" readonly required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="invoice_number">No Surat Jalan</label>
                                            <input type="text" class="form-control" id="invoice_number"
                                                name="invoice_number" value="{{ $barangKeluar->invoice_number }}" readonly
                                                required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="tanggal_keluar">Tanggal Keluar Barang</label>
                                            <input type="date" class="form-control" name="tanggal_keluar"
                                                value="{{ $barangKeluar->tanggal_keluar }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="barang_template">Barang Assy</label>
                                            <select class="form-control select2bs4" id="barang_template" multiple>
                                                @foreach ($barang_template as $barang_t)
                                                    <option value="{{ $barang_t->id }}"
                                                        {{ in_array($barang_t->id, $barangKeluar->details->pluck('template_id')->unique()->toArray()) ? 'selected' : '' }}>
                                                        {{ $barang_t->nama_template }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="customer_id">Customer</label>
                                            <select class="form-control select2bs4" id="customer_id" name="customer_id"
                                                required>
                                                <option value="" disabled>Select Customer</option>
                                                @foreach ($customers as $customer_t)
                                                    <option value="{{ $customer_t->id }}"
                                                        {{ $barangKeluar->customer_id == $customer_t->id ? 'selected' : '' }}>
                                                        {{ $customer_t->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="totalQty">Total Quantity</label>
                                            <input type="number" class="form-control qty-input" name="totalQty"
                                                value="{{ $barangKeluar->totalQty }}" required>
                                        </div>
                                    </div>

                                    <!-- Dynamic Assy Groups -->
                                    <div class="accordion" id="assyAccordion">
                                        <div id="assy-groups-container">
                                            @php $groupedItems = $barangKeluar->details->groupBy('template_id'); @endphp
                                            @foreach ($groupedItems as $templateId => $items)
                                                @php $templateName = $items->first()->template->nama_template ?? 'Unknown Assy'; @endphp
                                                <div class="assy-group card mt-3" id="assy-group-{{ $templateId }}"
                                                    data-template-id="{{ $templateId }}">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <button class="btn btn-link text-left collapsed" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#collapse-{{ $templateId }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse-{{ $templateId }}">
                                                                🔹 {{ $templateName }}
                                                            </button>
                                                        </h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-danger btn-sm remove-assy"
                                                                data-id="{{ $templateId }}">Remove Group</button>
                                                        </div>
                                                    </div>
                                                    <div id="collapse-{{ $templateId }}" class="collapse show"
                                                        data-parent="#assyAccordion">
                                                        <div class="card-body" id="assy-items-{{ $templateId }}">
                                                            @foreach ($items as $index => $item)
                                                                <div class="item-row row">
                                                                    <div class="form-group col-md-2">
                                                                        <label>Barang</label>
                                                                        <select class="form-control select2bs4"
                                                                            name="items[{{ $templateId }}][{{ $index }}][barang_id]"
                                                                            required>
                                                                            @foreach ($barangs as $barang)
                                                                                <option value="{{ $barang->id }}"
                                                                                    {{ $barang->id == $item->barang_id ? 'selected' : '' }}>
                                                                                    {{ $barang->part_number }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label>Deskripsi</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $item->barang->deskripsi ?? '' }}"
                                                                            readonly>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label>Stock</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $item->barang->stok ?? 0 }}"
                                                                            readonly>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label>Quantity</label>
                                                                        <input type="number"
                                                                            class="form-control qty-input"
                                                                            name="items[{{ $templateId }}][{{ $index }}][qty]"
                                                                            value="{{ $item->qty }}" min="1"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group col-md-2">
                                                                        <label>Remarks</label>
                                                                        <input type="text" class="form-control"
                                                                            name="items[{{ $templateId }}][{{ $index }}][remarks]"
                                                                            value="{{ $item->remarks }}">
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-md-2 d-flex align-items-end">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm remove-item">Remove</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="button" class="btn btn-success btn-sm add-item"
                                                                data-template="{{ $templateId }}">Add Another
                                                                Item</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
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

            $(function() {
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });

                function updateAssyGroups() {
                    const selectedTemplates = $('#barang_template').val() || [];
                    const totalQtyValue = parseFloat($('[name="totalQty"]').val()) || 1;

                    // Remove unselected groups
                    $('#assy-groups-container .assy-group').each(function() {
                        const groupId = $(this).data('template-id').toString();
                        if (!selectedTemplates.includes(groupId)) $(this).remove();
                    });

                    // Add new groups for newly selected templates
                    selectedTemplates.forEach(templateId => {
                        if ($('#assy-group-' + templateId).length) return; // already exists

                        const url = barangTemplateUrl.replace(':id', templateId);
                        $.get(url, function(response) {
                            const details = response.details || [];
                            const assyName = response.nama_template ?? `Barang Assy ${templateId}`;
                            if (!details.length) return;

                            // Build group card
                            const groupCard = `
                                <div class="assy-group card mt-3" id="assy-group-${templateId}" data-template-id="${templateId}">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <button class="btn btn-link text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse-${templateId}"
                                                aria-expanded="false" aria-controls="collapse-${templateId}">
                                                🔹 ${assyName}
                                            </button>
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-danger btn-sm remove-assy" data-id="${templateId}">Remove Group</button>
                                        </div>
                                    </div>
                                    <div id="collapse-${templateId}" class="collapse" data-parent="#assyAccordion">
                                        <div class="card-body" id="assy-items-${templateId}"></div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-success btn-sm add-item" data-template="${templateId}">Add Another Item</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#assy-groups-container').append(groupCard);

                            const $groupBody = $(`#assy-items-${templateId}`);

                            // Add each item row
                            details.forEach((item, index) => {
                                const calculatedQty = (item.qty || 0) * totalQtyValue;

                                const newItemRow = `
                                    <div class="item-row row">
                                        <div class="form-group col-md-2">
                                            <label>Barang</label>
                                            <select class="form-control select2bs4" name="items[${templateId}][${index}][barang_id]" required>
                                                <option value="${item.barang.id}" selected>${item.barang.part_number}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Deskripsi</label>
                                            <input type="text" class="form-control" value="${item.barang.deskripsi}" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Stock</label>
                                            <input type="text" class="form-control" value="${item.barang.stok}" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control qty-input" name="items[${templateId}][${index}][qty]" value="${calculatedQty}" min="1" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Remarks</label>
                                            <input type="text" class="form-control" name="items[${templateId}][${index}][remarks]" value="${item.remarks ?? ''}">
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                        </div>
                                    </div>
                                `;
                                $groupBody.append(newItemRow);

                                // Store original qty for totalQty multiplier
                                $groupBody.find('.item-row:last .qty-input').data('original',
                                    item.qty || 0);
                            });

                            $('.select2bs4').select2({
                                theme: 'bootstrap4'
                            });
                        });
                    });
                }

                // On change of template select
                $(document).on('change', '#barang_template', updateAssyGroups);

                // Add item button
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
                                        <option value="{{ $barang->id }}">{{ $barang->part_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2"><label>Deskripsi</label><input type="text" class="form-control" readonly></div>
                            <div class="form-group col-md-2"><label>Stock</label><input type="text" class="form-control" readonly></div>
                            <div class="form-group col-md-2"><label>Quantity</label><input type="number" class="form-control qty-input" name="items[${templateId}][${index}][qty]" min="1" required></div>
                            <div class="form-group col-md-2"><label>Remarks</label><input type="text" class="form-control" name="items[${templateId}][${index}][remarks]"></div>
                            <div class="form-group col-md-2 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></div>
                        </div>
                    `;
                    $groupBody.append(newItemRow);
                    $groupBody.find('.item-row:last .qty-input').data('original', 1);
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                });

                // Remove item
                $(document).on('click', '.remove-item', function() {
                    $(this).closest('.item-row').remove();
                });

                // Remove assy group
                $(document).on('click', '.remove-assy', function() {
                    const templateId = $(this).data('id');
                    $(`#assy-group-${templateId}`).remove();
                    const $select = $('#barang_template');
                    let values = $select.val() || [];
                    values = values.filter(v => v !== templateId.toString());
                    $select.val(values).trigger('change');
                });

                // totalQty multiplier
                $(document).on('input', '[name="totalQty"]', function() {
                    const multiplier = parseFloat($(this).val());
                    if (isNaN(multiplier) || multiplier === 0) return;

                    $('#assy-groups-container .item-row').each(function() {
                        const qtyInput = $(this).find('.qty-input');
                        let originalQty = qtyInput.data('original');
                        if (originalQty === undefined || originalQty === null) {
                            originalQty = parseFloat(qtyInput.val()) || 0;
                            qtyInput.data('original', originalQty);
                        }
                        qtyInput.val(originalQty * multiplier);
                    });
                });
            });
        </script>
    </div>
@endsection
