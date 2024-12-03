<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ public_path('backend/img/logo.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .w-100 {
            width: 100%;
        }
        h3 {
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ced4da;
            padding: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
        }
        .no-line {
            border-top: none;
        }
        .thick-line {
            border-top: 2px solid #ced4da;
        }
        p {
            margin: 0;
        }
        .invoice-header {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <table class="w-100">
            <tr>
                <td>
                    <img src="{{ public_path($companyProfile->image) }}" height="60" alt="Company Logo">
                </td>
                <td class="text-right">
                    <h2>Laporan {{ $judul }}</h2>
                </td>
            </tr>
        </table>
        <table class="w-100">
            <tr>                    
                <td style="width: 60%">
                    <p>
                        {{ $companyProfile->name }} <br>
                        {{ $companyProfile->address }} <br>
                        Phone: {{ $companyProfile->phone }} <br> 
                        Email: {{ $companyProfile->email }}
                    </p>
                </td>
                <td class="text-right">
                    <p>
                        <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} <br>
                        <strong>Dari tanggal:</strong> {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} <br>
                        <strong>Sampai tanggal:</strong> {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }} <br>
                    </p>
                </td>
            </tr>
        </table>       
    </div>
    
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    @if ($type == 'barang_keluar')
                        <th class="text-left"><strong>PO No.</strong></th>
                    @endif
                    <th class="text-left"><strong>Invoice No.</strong></th>
                    <th class="text-left"><strong>Invoice Date</strong></th>
                    <th class="text-left"><strong>Barang</strong></th>
                    <th class="text-center"><strong>Qty</strong></th>
                </tr>
            </thead>
            <tbody>
                @if ($datatransaksi->isEmpty())
                    <tr>
                        <td {{ $type == 'barang_keluar' ? 'colspan=5' : 'colspan=4' }} class="no-data text-center">Data transaksi tidak ditemukan untuk periode yang dipilih.</td>
                    </tr>
                @else
                    @foreach ($datatransaksi as $detailitem)
                        <!-- Display the invoice header information -->
                        <tr class="invoice-header">
                            @if ($type == 'barang_keluar')
                                <td>
                                    {{ $detailitem->po_number }}
                                </td>
                            @endif
                            <td>
                                @if ($type == 'barang_masuk')
                                    {{ $detailitem->purchaseOrder->invoice_number }}
                                @else
                                    {{ $detailitem->invoice_number }}
                                @endif
                            </td>
                            <td>{{ $detailitem->created_at->translatedFormat('d M Y') }}</td>
                            <td colspan="2">Dibuat Oleh: {{ $detailitem->user->name }}</td>
                        </tr>
                        <tr>
                            @if ($type == 'barang_keluar')
                                <td></td>
                            @endif
                            <td>
                                @if ($type == 'barang_masuk')
                                    <strong>Vendor</strong>
                                @elseif ($type == 'barang_keluar')
                                    <strong>Customer</strong>
                                @else
                                    
                                @endif
                            </td>
                            <td><strong>Part Number</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- Display each item's details under the current invoice -->
                        @foreach ($detailitem->details as $item)
                            <tr>
                                @if ($type == 'barang_keluar')
                                    <td></td>
                                @endif
                                <td>
                                    @if ($type == 'barang_masuk')
                                        {{ $detailitem->purchaseOrder->vendor ?? '-' }}
                                    @elseif ($type == 'barang_keluar')
                                        {{ $detailitem->customer->name ?? '-' }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td>{{ $item->barang->part_number }}</td>
                                <td> {{ $item->barang->deskripsi }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                            </tr>
                        @endforeach

                        <!-- Display the total for the current invoice -->
                        <tr>
                            <td {{ $type == 'barang_keluar' ? 'colspan=4' : 'colspan=3' }} class="no-line text-right"><strong>Total</strong></td>
                            <td class="no-line text-center"><strong>{{ $detailitem->details->sum(fn($item) => $item->qty) }}</strong></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td {{ $type == 'barang_keluar' ? 'colspan=4' : 'colspan=3' }} class="text-right"><strong>Sub Total</strong></td>
                    <td class="text-center"><strong>{{ $datatransaksi->flatMap(function($item) { return $item->details; })->sum('qty'); }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</body>
</html>
