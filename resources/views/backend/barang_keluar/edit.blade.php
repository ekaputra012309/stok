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
                                    </div>

                                    <!-- 🔹 Non-Assy Items Table Section -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">Barang Non-Assy</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-success btn-sm" id="add-non-assy">Add
                                                    Barang</button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped mb-0"
                                                    style="min-width: 1028px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 200px;">Barang</th>
                                                            <th style="width: 200px;">Deskripsi</th>
                                                            <th style="width: 100px;">Stock</th>
                                                            <th style="width: 100px;">Quantity</th>
                                                            <th style="width: 200px;">Remarks</th>
                                                            <th style="width: 100px;" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="non-assy-container">
                                                        @php
                                                            $nonAssyItems = $barangKeluar->details->whereNull(
                                                                'template_id',
                                                            );
                                                        @endphp
                                                        @foreach ($nonAssyItems as $index => $item)
                                                            <tr class="item-row">
                                                                <td>
                                                                    @php
                                                                        $barangOptionsHtml =
                                                                            '<option value="" disabled>Select Barang</option>';
                                                                        foreach ($barangs as $barang) {
                                                                            $isSelected =
                                                                                $barang->id == $item->barang_id
                                                                                    ? 'selected'
                                                                                    : '';
                                                                            $barangOptionsHtml .=
                                                                                '<option value="' .
                                                                                $barang->id .
                                                                                '" ' .
                                                                                'data-deskripsi="' .
                                                                                e($barang->deskripsi) .
                                                                                '" ' .
                                                                                'data-stok="' .
                                                                                $barang->stok .
                                                                                '" ' .
                                                                                $isSelected .
                                                                                '>' .
                                                                                e($barang->part_number) .
                                                                                '</option>';
                                                                        }
                                                                    @endphp
                                                                    <select class="form-control select2bs4"
                                                                        name="items[non_assy][{{ $index }}][barang_id]"
                                                                        required>
                                                                        {!! $barangOptionsHtml !!}
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control deskripsi-input"
                                                                        value="{{ $item->barang->deskripsi ?? '' }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control stock-input"
                                                                        value="{{ $item->barang->stok ?? 0 }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        name="items[non_assy][{{ $index }}][qty]"
                                                                        value="{{ $item->qty }}" min="1"
                                                                        required>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="items[non_assy][{{ $index }}][remarks]"
                                                                        value="{{ $item->remarks }}">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-item">Remove</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dynamic Assy Groups Table Section -->
                                    <div class="accordion" id="assyAccordion">
                                        <div id="assy-groups-container">
                                            @php
                                                $groupedItems = $barangKeluar->details
                                                    ->whereNotNull('template_id')
                                                    ->groupBy('template_id');
                                            @endphp
                                            @foreach ($groupedItems as $templateId => $items)
                                                @php
                                                    $templateName =
                                                        $items->first()->template->nama_template ?? 'Unknown Assy';
                                                    $currentGroupTotalQty =
                                                        $groupedDetails[$templateId]['total_group_qty'] ?? 1;
                                                @endphp
                                                <div class="assy-group card mt-3" id="assy-group-{{ $templateId }}"
                                                    data-template-id="{{ $templateId }}">
                                                    <div
                                                        class="card-header d-flex align-items-center justify-content-between">
                                                        <!-- Left side: Title + Total Quantity Input -->
                                                        <div class="d-flex align-items-center flex-wrap flex-grow-1 mr-3">
                                                            <button
                                                                class="btn btn-link text-left collapsed p-0 text-dark font-weight-bold mr-4"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapse-{{ $templateId }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse-{{ $templateId }}">
                                                                🔹 {{ $templateName }}
                                                            </button>

                                                            <div class="form-inline">
                                                                <label class="mr-2 mb-0"><small>Total
                                                                        Quantity:</small></label>
                                                                <input type="number"
                                                                    class="form-control form-control-sm group-total-qty"
                                                                    name="total_group_qty[{{ $templateId }}]"
                                                                    data-template="{{ $templateId }}"
                                                                    value="{{ $currentGroupTotalQty }}" min="1"
                                                                    style="width: 80px;">
                                                            </div>
                                                        </div>

                                                        <!-- Right side: Only the Remove Group Button -->
                                                        <div class="card-tools m-0">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-assy"
                                                                data-id="{{ $templateId }}">Remove Group</button>
                                                        </div>
                                                    </div>
                                                    <div id="collapse-{{ $templateId }}" class="collapse show"
                                                        data-parent="#assyAccordion">
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped mb-0"
                                                                    style="min-width: 1028px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Barang</th>
                                                                            <th>Deskripsi</th>
                                                                            <th style="width: 100px;">Stock</th>
                                                                            <th style="width: 100px;">Quantity</th>
                                                                            <th style="width: 200px;">Remarks</th>
                                                                            <th class="text-center">
                                                                                Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="assy-items-{{ $templateId }}">
                                                                        @foreach ($items as $index => $item)
                                                                            @php
                                                                                $baseQty =
                                                                                    $currentGroupTotalQty > 0
                                                                                        ? $item->qty /
                                                                                            $currentGroupTotalQty
                                                                                        : $item->qty;
                                                                            @endphp
                                                                            <tr class="item-row">
                                                                                <td>
                                                                                    @php
                                                                                        $barangOptionsHtml =
                                                                                            '<option value="" disabled>Select Barang</option>';
                                                                                        foreach ($barangs as $barang) {
                                                                                            $isSelected =
                                                                                                $barang->id ==
                                                                                                $item->barang_id
                                                                                                    ? 'selected'
                                                                                                    : '';
                                                                                            $barangOptionsHtml .=
                                                                                                '<option value="' .
                                                                                                $barang->id .
                                                                                                '" ' .
                                                                                                'data-deskripsi="' .
                                                                                                e($barang->deskripsi) .
                                                                                                '" ' .
                                                                                                'data-stok="' .
                                                                                                $barang->stok .
                                                                                                '" ' .
                                                                                                $isSelected .
                                                                                                '>' .
                                                                                                e(
                                                                                                    $barang->part_number,
                                                                                                ) .
                                                                                                '</option>';
                                                                                        }
                                                                                    @endphp
                                                                                    <select class="form-control select2bs4"
                                                                                        name="items[{{ $templateId }}][{{ $index }}][barang_id]"
                                                                                        required>
                                                                                        {!! $barangOptionsHtml !!}
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control deskripsi-input"
                                                                                        value="{{ $item->barang->deskripsi ?? '' }}"
                                                                                        readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control stock-input"
                                                                                        value="{{ $item->barang->stok ?? 0 }}"
                                                                                        readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        class="form-control qty-input"
                                                                                        name="items[{{ $templateId }}][{{ $index }}][qty]"
                                                                                        value="{{ $item->qty }}"
                                                                                        data-original="{{ $baseQty }}"
                                                                                        min="1" required>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="items[{{ $templateId }}][{{ $index }}][remarks]"
                                                                                        value="{{ $item->remarks }}">
                                                                                </td>
                                                                                <td class="text-center align-middle">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger btn-sm remove-item">Remove</button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
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

            // Reusable string options generated for dynamic rows
            const barangOptionsTemplate = `
                <option value="" disabled selected>Select Barang</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}" 
                            data-deskripsi="{{ addslashes($barang->deskripsi) }}" 
                            data-stok="{{ $barang->stok }}">
                        {{ $barang->part_number }}
                    </option>
                @endforeach
            `;

            $(function() {
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });

                function updateAssyGroups() {
                    const selectedTemplates = $('#barang_template').val() || [];

                    // Remove unselected groups
                    $('#assy-groups-container .assy-group').each(function() {
                        const groupId = $(this).data('template-id').toString();
                        if (!selectedTemplates.includes(groupId)) {
                            $(this).remove();
                        }
                    });

                    // Add new groups for newly selected templates
                    selectedTemplates.forEach(templateId => {
                        if ($('#assy-group-' + templateId).length > 0) return;

                        const url = barangTemplateUrl.replace(':id', templateId);
                        $.get(url, function(response) {
                            const details = response.details || [];
                            const assyName = response.nama_template ?? `Barang Assy ${templateId}`;
                            if (!details.length) return;

                            const groupCard = `
                                <div class="assy-group card mt-3" id="assy-group-${templateId}" data-template-id="${templateId}">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-wrap flex-grow-1 mr-3">
                                            <button class="btn btn-link text-left collapsed p-0 text-dark font-weight-bold mr-4" type="button"
                                                data-toggle="collapse" data-target="#collapse-${templateId}"
                                                aria-expanded="false" aria-controls="collapse-${templateId}">
                                                🔹 ${assyName}
                                            </button>
                                            <div class="form-inline">
                                                <label class="mr-2 mb-0"><small>Total Quantity:</small></label>
                                                <input type="number"
                                                    class="form-control form-control-sm group-total-qty"
                                                    name="total_group_qty[${templateId}]"
                                                    data-template="${templateId}"
                                                    value="1"
                                                    min="1" style="width: 80px;">
                                            </div>
                                        </div>
                                        <div class="card-tools m-0">
                                            <button type="button" class="btn btn-danger btn-sm remove-assy" data-id="${templateId}">Remove Group</button>
                                        </div>
                                    </div>

                                    <div id="collapse-${templateId}" class="collapse show" data-parent="#assyAccordion">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped mb-0" style="min-width: 1028px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 200px;">Barang</th>
                                                            <th style="width: 200px;">Deskripsi</th>
                                                            <th style="width: 100px;">Stock</th>
                                                            <th style="width: 100px;">Quantity</th>
                                                            <th style="width: 200px;">Remarks</th>
                                                            <th style="width: 100px;" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="assy-items-${templateId}">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#assy-groups-container').append(groupCard);

                            const $groupBody = $(`#assy-items-${templateId}`);

                            details.forEach((item, index) => {
                                const baseQty = item.qty || 0;

                                const newItemRow = `
                                <tr class="item-row">
                                    <td>
                                        <select class="form-control select2bs4" name="items[${templateId}][${index}][barang_id]" required>
                                            <option value="${item.barang.id}" 
                                                data-deskripsi="${item.barang.deskripsi}" 
                                                data-stok="${item.barang.stok}" 
                                                selected>
                                                ${item.barang.part_number}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control deskripsi-input" value="${item.barang.deskripsi}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control stock-input" value="${item.barang.stok}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty-input" name="items[${templateId}][${index}][qty]" value="${baseQty}" min="1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[${templateId}][${index}][remarks]" value="${item.remarks ?? ''}">
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                    </td>
                                </tr>
                                `;
                                $groupBody.append(newItemRow);
                                $groupBody.find('tr.item-row:last .qty-input').data('original',
                                    baseQty);
                            });

                            $groupBody.find('.select2bs4').select2({
                                theme: 'bootstrap4'
                            });
                        });
                    });
                }

                $('#barang_template').on('change', updateAssyGroups);

                // Add Non-Assy Table Row
                $('#add-non-assy').on('click', function() {
                    const $container = $('#non-assy-container');
                    const index = $container.find('tr.item-row').length;

                    const newItem = `
                        <tr class="item-row">
                            <td>
                                <select class="form-control select2bs4" name="items[non_assy][${index}][barang_id]" required>
                                    ${barangOptionsTemplate}
                                </select>
                            </td>
                            <td><input type="text" class="form-control deskripsi-input" readonly></td>
                            <td><input type="text" class="form-control stock-input" readonly></td>
                            <td><input type="number" class="form-control" name="items[non_assy][${index}][qty]" min="1" required></td>
                            <td><input type="text" class="form-control" name="items[non_assy][${index}][remarks]"></td>
                            <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                        </tr>
                    `;
                    $container.append(newItem);
                    $container.find('tr.item-row:last .select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                });

                // Remove table row item
                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr.item-row').remove();
                });

                // Remove assy group
                $(document).on('click', '.remove-assy', function() {
                    const templateId = $(this).data('id');
                    $(`#assy-group-${templateId}`).remove();
                    const $select = $('#barang_template');
                    let values = $select.val() || [];
                    values = values.filter(v => v !== templateId.toString());
                    $select.val(values).trigger('change.select2');
                });

                // Group quantity multiplier
                $(document).on('input', '.group-total-qty', function() {
                    const multiplier = parseFloat($(this).val());
                    const templateId = $(this).data('template');
                    if (isNaN(multiplier) || multiplier <= 0) return;

                    $(`#assy-items-${templateId} tr.item-row`).each(function() {
                        const qtyInput = $(this).find('.qty-input');
                        let originalBaseQty = qtyInput.data('original');

                        if (originalBaseQty === undefined) {
                            originalBaseQty = parseFloat(qtyInput.val()) || 0;
                            qtyInput.data('original', originalBaseQty);
                        }
                        qtyInput.val(Math.round(originalBaseQty * multiplier));
                    });
                });

                // Auto-fill description & stock
                $(document).on('change', 'select[name^="items"][name*="[barang_id]"]', function() {
                    const $selected = $(this).find(':selected');
                    const deskripsi = $selected.data('deskripsi') || '';
                    const stok = $selected.data('stok') || 0;

                    const $row = $(this).closest('tr.item-row');
                    $row.find('.deskripsi-input').val(deskripsi);
                    $row.find('.stock-input').val(stok);
                });
            });
        </script>
    </div>
@endsection
