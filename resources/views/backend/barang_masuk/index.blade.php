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
                                    <select name="purchase_order_id" id="purchase_order_id" class="form-control" required>
                                        <option value="">-- Select Approved PO --</option>
                                        @foreach ($approvedPurchaseOrders as $purchaseOrder)
                                            <option value="{{ $purchaseOrder->id }}">
                                                {{ $purchaseOrder->invoice_number }} (Total Qty: {{ $purchaseOrder->items->sum('qty') }})
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
                                    @foreach ($databarang_masuk as $barangMasuk)
                                        <tr>
                                            <td>
                                                <a class="btn btn-xs btn-dark" href="{{ route('barang_masuk.show', $barangMasuk->id) }}">
                                                    <i class="fas fa-eye"></i> Show
                                                </a>
                                            </td>
                                            <td>{{ $barangMasuk->purchaseOrder->invoice_number }}</td>
                                            <td>
                                                @foreach ($barangMasuk->details as $item)
                                                    <strong>({{ $item->barang->stok }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($barangMasuk->details as $item)
                                                    <strong>({{ $item->barang->part_number }})</strong> <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($barangMasuk->details as $item)
                                                    {{ $item->barang->deskripsi }} <strong>({{ $item->qty }} @if ($item->barang->satuan) {{ $item->barang->satuan->name }} @endif)</strong><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @php
                                                    $totalQty = $barangMasuk->details->sum('qty');
                                                    echo $totalQty;
                                                @endphp
                                            </td>
                                            <td>{{ $barangMasuk->note }}</td>
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
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   true,
        });
    </script>
</div>
@endsection
