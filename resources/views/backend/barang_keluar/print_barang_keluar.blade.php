<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ public_path('backend/img/logo.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $barangKeluar->invoice_number }}</title>    
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
                <td style="width: 10%">
                    <img src="{{ public_path($companyProfile->image) }}" height="80" alt="Company Logo">
                </td>
                <td class="text-left">
                    <p>
                        <span style="font-size: 1.5em">
                            <strong>
                            {{ $companyProfile->name }}
                            </strong>
                        </span> <br>
                        {{ $companyProfile->address }} <br>
                        Phone: {{ $companyProfile->phone }} <br> 
                        Email: {{ $companyProfile->email }}
                    </p>
                </td>
            </tr>
        </table>
        <h2 class="text-center"><u>SURAT JALAN</u> </h2>
        <table class="w-100">
            <tr> 
                <td style="width: 6%; vertical-align: top">
                    <strong>To :</strong>
                </td>                   
                <td style="width: 58%; vertical-align: top">
                    <p>
                        <strong>
                            {{ $barangKeluar->customer->name ?? '' }}
                        </strong> <br>
                        {{ $barangKeluar->customer->alamat ?? '' }} <br>
                        Phone: {{ $barangKeluar->customer->phone ?? '' }}
                    </p>
                </td>
                <td class="text-right">
                    <p>
                        <strong>No :</strong> <br>
                        <strong>Date :</strong> <br>
                        <strong>No PO :</strong> <br>
                        <strong>PO Date :</strong> <br>
                        <!-- <strong>Customer:</strong> {{ $barangKeluar->customer->name }} <br> -->
                        
                        
                        <!-- <strong>Dibuat Oleh:</strong> {{ $barangKeluar->user->name }} <br>
                        <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} <br> -->
                    </p>
                </td>
                <td>
                    <p>
                        {{ $barangKeluar->invoice_number }} <br>
                        {{ now()->translatedFormat('d F Y') }} <br>
                        {{ $barangKeluar->po_number }} <br>
                        {{ $barangKeluar->created_at->translatedFormat('d F Y') }} <br>
                    </p>
                </td>
            </tr>
        </table>       
    </div>
    
    <div class="row">
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th><strong>No</strong></th>
                    <th><strong>Product Name</strong></th>
                    <th><strong>Qty</strong></th>
                    <th><strong>Uom</strong></th>
                    <th><strong>Remarks</strong></th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1 @endphp
                @foreach ($barangKeluar->details as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td><strong>{{ $item->barang->part_number }}</strong> - {{ $item->barang->deskripsi }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-center">{{ $item->barang->satuan->name }}</td>
                        <td class="text-center">{{ $item->remarks }}</td>
                    </tr>
                @endforeach
                <!-- <tr>
                    <td colspan="2" class="no-line text-left"><strong>Total</strong></td>
                    <td class="no-line text-center"><strong>{{ $barangKeluar->details->sum(fn($item) => $item->qty) }}</strong></td>
                </tr> -->
            </tbody>
        </table>
        <br>
        <table style="width: 100%; border: 1px solid grey;">
            <tbody>
                <tr>
                    <td>
                        EXPEDITION <br>
                        NO. VEHICLE 
                    </td>
                    <td style="border-left: 0px solid #000 !important; width: 40%">
                        DATE RECEIPT : <br>
                        T.T DRIVER
                    </td>
                </tr>
                <tr>
                    <td>
                        <br> 
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%;">
            <tbody>
                <tr class="text-center">
                    <td style="width: 30%">
                        {{ $barangKeluar->customer->name }}
                    </td>
                    <td></td>
                    <td style="width: 30%">
                        RECEIVED BY :
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br> <br> <br> <br> <br>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid grey !important; width: 30%"></td>
                    <td></td>
                    <td style="border-bottom: 1px solid grey !important; width: 30%"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
