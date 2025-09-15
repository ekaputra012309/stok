@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Barang Masuk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Barang Masuk</li>
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
                                <h3 class="card-title">Select Approved Purchase Order</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('barang_masuk.process') }}" method="POST" class="form-inline">
                                    @csrf
                                    <div class="form-group mr-2">
                                        <label for="purchase_order_id" class="mr-2">Pilih PO:</label>
                                        <select name="purchase_order_id" id="purchase_order_id" class="form-control"
                                            required>
                                            <option value="">-- Select Approved PO --</option>
                                            @foreach ($approvedPurchaseOrders as $purchaseOrder)
                                                <option value="{{ $purchaseOrder->id }}">
                                                    {{ $purchaseOrder->invoice_number }} (Total Qty:
                                                    {{ $purchaseOrder->items->sum('qty') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary ml-2">Proses</button>
                                </form>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No PO</th>
                                            <th>Stok Aktual</th>
                                            <th>Part Number</th>
                                            <th>Nama Barang (Qty Masuk)</th>
                                            <th>Total Qty</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedBarangMasuk as $poId => $barangMasukGroup)
                                            @php
                                                $purchaseOrder = $barangMasukGroup->first()->purchaseOrder;
                                                $poItems = $purchaseOrder->items;

                                                // Sum total received qty per barang_id
                                                $receivedItems = [];

                                                foreach ($barangMasukGroup as $barangMasuk) {
                                                    foreach ($barangMasuk->details as $detail) {
                                                        $barangId = $detail->barang_id;
                                                        $receivedItems[$barangId] =
                                                            ($receivedItems[$barangId] ?? 0) + $detail->qty;
                                                    }
                                                }

                                                $totalReceived = array_sum($receivedItems);
                                                $totalOrdered = $poItems->sum('qty');
                                            @endphp

                                            <tr>
                                                <td>
                                                    <a class="btn btn-xs btn-dark"
                                                        href="{{ route('barang_masuk.show', $barangMasukGroup->first()->id) }}">
                                                        <i class="fas fa-eye"></i> Show
                                                    </a>

                                                    @if ($totalReceived < $totalOrdered)
                                                        <form action="{{ route('barang_masuk.process') }}" method="POST"
                                                            class="form-inline mt-1">
                                                            @csrf
                                                            <input type="hidden" name="purchase_order_id"
                                                                value="{{ $purchaseOrder->id }}">
                                                            <button type="submit" class="btn btn-xs btn-primary">
                                                                <i class="fas fa-pen"></i> Proses
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>

                                                <td>{{ $purchaseOrder->invoice_number }}</td>

                                                <td>
                                                    @foreach ($poItems as $item)
                                                        {{ $item->barang->stok }}
                                                        @if ($item->barang->satuan)
                                                            {{ $item->barang->satuan->name }}
                                                        @endif
                                                        <br>
                                                    @endforeach
                                                </td>

                                                <td>
                                                    @foreach ($poItems as $item)
                                                        {{ $item->barang->part_number }} <br>
                                                    @endforeach
                                                </td>

                                                <td>
                                                    @foreach ($poItems as $item)
                                                        {{ $item->barang->deskripsi }}
                                                        <strong>
                                                            ({{ $receivedItems[$item->barang_id] ?? 0 }}
                                                            @if ($item->barang->satuan)
                                                                {{ $item->barang->satuan->name }}
                                                            @endif)
                                                        </strong>
                                                        <br>
                                                    @endforeach
                                                </td>

                                                <td>{{ $totalReceived }}</td>

                                                <td>
                                                    {{-- Sort descending and get first to get the latest note --}}
                                                    {{ $barangMasukGroup->sortByDesc('created_at')->first()->note ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                scrollY: "500px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: true,
            });
        </script>
    </div>
@endsection
