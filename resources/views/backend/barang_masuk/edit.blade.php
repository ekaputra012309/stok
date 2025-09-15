@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Process Barang Masuk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('barang_masuk.index') }}">Barang Masuk</a></li>
                            <li class="breadcrumb-item active">Process</li>
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
                                <h3 class="card-title">Verifikasi Barang PO</h3>
                            </div>

                            <form action="{{ route('barang_masuk.store') }}" method="POST" id="barang-masuk-form">
                                @csrf
                                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">

                                <div class="card-body">
                                    <div class="row">
                                        <!-- Invoice Number Field -->
                                        <div class="form-group col-md-3">
                                            <label for="invoice_number">No PO</label>
                                            <input type="text" class="form-control" name="invoice_number"
                                                id="invoice_number" readonly value="{{ $purchaseOrder->invoice_number }}">
                                            <input type="hidden" name="invoice_number_hidden"
                                                value="{{ $purchaseOrder->invoice_number }}">
                                        </div>

                                        <!-- Tanggal Masuk Field -->
                                        <div class="form-group col-md-3">
                                            <label for="tanggal_masuk">Tanggal Masuk Barang</label>
                                            <input type="date" class="form-control" name="tanggal_masuk"
                                                id="tanggal_masuk" value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <!-- Items List -->
                                    <div id="items-container">
                                        @foreach ($purchaseOrder->items as $index => $item)
                                            @php
                                                $barangId = $item->barang->id;
                                                $detail = $existingDetails[$barangId] ?? null;

                                                $qty = $detail->qty ?? $item->qty;
                                                $isVerified = $detail && $detail->qty_verified == 1;
                                            @endphp

                                            <div class="item-row row">
                                                <div class="form-group col-md-4">
                                                    <label for="barang_id">Barang</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $item->barang->deskripsi }}" readonly>
                                                    <input type="hidden" name="items[{{ $index }}][barang_id]"
                                                        value="{{ $item->barang->id }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="qty">Quantity</label>
                                                    <input type="number" class="form-control qty-input"
                                                        name="items[{{ $index }}][qty]" value="{{ $item->qty }}"
                                                        required min="1">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="verify_{{ $index }}">Verifikasi Qty Barang</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden"
                                                            name="items[{{ $index }}][qty_verified]" value="0">
                                                        <input type="checkbox" class="custom-control-input toggle-verify"
                                                            id="verify_{{ $index }}"
                                                            name="items[{{ $index }}][qty_verified]" value="1"
                                                            {{ $isVerified ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="verify_{{ $index }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="form-group">
                                        <label for="note">Catatan (optional)</label>
                                        <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Process</button>
                                    <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <script>
            $(function() {
                // Toggle quantity input based on verification toggle
                $(document).on('change', '.toggle-verify', function() {
                    const qtyInput = $(this).closest('.item-row').find('.qty-input');
                    qtyInput.prop('readonly', $(this).is(':checked')); // Make quantity read-only if checked
                });
            });
        </script>

    </div>
@endsection
