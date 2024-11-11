<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ public_path('backend/img/logo.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $barangKeluar->invoice_number }}</title>    
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
                        <strong>Invoice No.</strong> {{ $barangKeluar->invoice_number }} <br>
                        <strong>Invoice Date:</strong> {{ $barangKeluar->created_at->translatedFormat('d F Y') }} <br>
                        <strong>Dibuat Oleh:</strong> {{ $barangKeluar->user->name }} <br>
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
                    <th class="text-left"><strong>Barang</strong></th>
                    <th class="text-center"><strong>Qty</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluar->details as $item)
                    <tr>
                        <td>{{ $item->barang->deskripsi }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="no-line text-left"><strong>Total</strong></td>
                    <td class="no-line text-center"><strong>{{ $barangKeluar->details->sum(fn($item) => $item->qty) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
