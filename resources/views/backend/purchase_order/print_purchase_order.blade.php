<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ public_path('backend/img/logo.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $purchaseOrder->invoice_number }}</title>    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
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
                    <h1>Invoice</h1>
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
                        <strong>No Surat Jalan:</strong> {{ $purchaseOrder->invoice_number }} <br>
                        <strong>Vendor:</strong> {{ $purchaseOrder->vendor }} <br>
                        <strong>Invoice Date:</strong> {{ $purchaseOrder->created_at->translatedFormat('d F Y') }} <br>
                        <strong>Dibuat Oleh:</strong> {{ $purchaseOrder->user->name }} <br>
                        <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} <br>
                    </p>
                </td>
            </tr>
        </table>       
    </div>
    
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-left"><strong>Part Number</strong></th>
                    <th class="text-left"><strong>Barang</strong></th>
                    <th class="text-center"><strong>Qty</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->items as $item)
                    <tr>
                        <td><strong>({{ $item->barang->part_number }})</strong></td>
                        <td>{{ $item->barang->deskripsi }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="no-line text-left"><strong>Total</strong></td>
                    <td class="no-line text-center"><strong>{{ $purchaseOrder->items->sum(fn($item) => $item->qty ) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
