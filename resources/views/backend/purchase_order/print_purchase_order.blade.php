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
                    <h3>Invoice</h3>
                </td>
            </tr>
        </table>
        <table class="w-100">
            <tr>                    
                <td>
                    <p>
                        {{ $companyProfile->name }} <br>
                        {{ $companyProfile->address }} <br>
                        Phone: {{ $companyProfile->phone }} <br> 
                        Email: {{ $companyProfile->email }}
                    </p>
                </td>
                <td class="text-right">
                    <p>
                        <strong>Invoice No.</strong> {{ $purchaseOrder->invoice_number }} <br>
                        <strong>Invoice Date:</strong> {{ $purchaseOrder->created_at->translatedFormat('d F Y') }} <br>
                        <strong>Created by:</strong> {{ $purchaseOrder->user->name }} <br>
                    </p>
                </td>
            </tr>
        </table>       
    </div>
    
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th><strong>Barang</strong></th>
                    <th class="text-center"><strong>Harga</strong></th>
                    <th class="text-center"><strong>Qty</strong></th>
                    <th class="text-right"><strong>Total</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->items as $item)
                    <tr>
                        <td>{{ $item->barang->deskripsi }}</td>
                        <td class="text-center">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="thick-line"></td>
                    <td class="thick-line"></td>
                    <td class="thick-line text-center"><strong>Subtotal</strong></td>
                    <td class="thick-line text-right">Rp {{ number_format($purchaseOrder->items->sum(fn($item) => $item->qty * $item->harga), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="no-line"></td>
                    <td class="no-line"></td>
                    <td class="no-line text-center"><strong>Total</strong></td>
                    <td class="no-line text-right"><strong>Rp {{ number_format($purchaseOrder->items->sum(fn($item) => $item->qty * $item->harga), 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
