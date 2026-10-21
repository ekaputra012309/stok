<table style="width: 100%;">
    <tr>
        <td style="width: 500px">
            <p>
                {{ $companyProfile->name }} <br>
                {{ $companyProfile->address }} <br>
                Phone: {{ $companyProfile->phone }} <br>
                Email: {{ $companyProfile->email }}
            </p>
        </td>
        <td colspan="2" style="text-align: right; width: 300px; vertical-align: top;">
            <p>
                <strong>Nama:</strong> {{ $barangTemplate->nama_template }} <br>
                <strong>Tanggal:</strong> {{ $barangTemplate->created_at->translatedFormat('d F Y') }} <br>
                <strong>Dibuat Oleh:</strong> {{ $barangTemplate->user->name }} <br>
                <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} <br>
            </p>
        </td>
    </tr>
</table>
<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left; border: 1px solid #000;"><strong>Part Number</strong></th>
            <th style="text-align: left; border: 1px solid #000;"><strong>Barang</strong></th>
            <th style="text-align: center; border: 1px solid #000;"><strong>Qty</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barangTemplate->details as $item)
            <tr>
                <td style="border: 1px solid #000;"><strong>({{ $item->barang->part_number }})</strong></td>
                <td style="border: 1px solid #000;">{{ $item->barang->deskripsi }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $item->qty }}
                    {{ $item->barang->satuan ? $item->barang->satuan->name : '' }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: left; border: 1px solid #000;"><strong>Total</strong></td>
            <td style="text-align: center; border: 1px solid #000;">
                @php
                    $qtyBySatuan = $barangTemplate->details->groupBy(
                        fn($item) => $item->barang->satuan->name ?? 'Tanpa Satuan',
                    );
                @endphp

                @foreach ($qtyBySatuan as $satuan => $items)
                    <strong>
                        {{ number_format($items->sum('qty'), 0, ',', '.') }}
                        {{ $satuan }}
                    </strong>
                    <br>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
