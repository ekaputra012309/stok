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
                    <th class="text-left"><strong>Invoice No.</strong></th>
                    <th class="text-left"><strong>Invoice Date</strong></th>
                    <th class="text-left"><strong>Barang</strong></th>
                    <th class="text-center"><strong>Qty</strong></th>
                </tr>
            </thead>
            <tbody>
                @if ($datatransaksi->isEmpty())
                    <tr>
                        <td colspan="4" class="no-data text-center">Data transaksi tidak ditemukan untuk periode yang dipilih.</td>
                    </tr>
                @else
                    @foreach ($datatransaksi as $detailitem)
                        <!-- Display the invoice header information -->
                        <tr class="invoice-header">
                            <td>
                                @if ($type == 'barang_masuk')
                                    {{ $detailitem->barangMasuk->purchaseOrder->invoice_number }}
                                @elseif ($type == 'barang_keluar')
                                    {{ $detailitem->barangKeluar->invoice_number }}
                                @else
                                    {{ optional($detailitem->barangbroken)->invoice_number }}
                                @endif
                            </td>
                            <td>{{ $detailitem->created_at->translatedFormat('d M Y') }}</td>
                            <td colspan="2">Dibuat Oleh: 
                                @if ($type == 'barang_masuk')    
                                    {{ $detailitem->barangMasuk->user->name }}
                                @else
                                    {{ $detailitem->user->name }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>Part Number</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- Display each item's details under the current invoice -->                        
                            <tr>
                                <td></td>
                                <td>{{ $detailitem->barang->part_number }}</td>
                                <td> {{ $detailitem->barang->deskripsi }}</td>
                                <td class="text-center">{{ $detailitem->qty }}</td>
                            </tr>
                        <!-- Display the total for the current invoice -->
                        <!-- <tr>
                            <td colspan="3" class="no-line text-left"><strong>Total</strong></td>
                            <td class="no-line text-center"><strong>
                                {{ $datatransaksi->where('id', $detailitem->id)->sum('qty') }}
                            </td>
                        </tr> -->
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ $datatransaksi->sum('qty'); }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</body>
</html>
