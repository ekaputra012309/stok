<table style="width: 100%;">
    <thead>
        <tr>
            <th colspan="{{ $type_transaksi == 'barang_keluar' ? 5 : 4 }}" style="text-align: center;">Laporan {{ $judul }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="{{ $type_transaksi == 'barang_keluar' ? 5 : 4 }}" style="text-align: center;">
                dari tanggal {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} 
                s/d tanggal {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </tbody>
</table>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            @if ($type_transaksi == 'barang_keluar')
                <th style="border: 1px solid black; padding: 8px; text-align: left; width: 150px"><strong>PO No.</strong></th>
            @endif
            <th style="border: 1px solid black; padding: 8px; text-align: left; width: 120px"><strong>Invoice No.</strong></th>
            <th style="border: 1px solid black; padding: 8px; text-align: left; width: 100px"><strong>Invoice Date</strong></th>
            @if ($type_transaksi == 'barang_masuk')
                <th style="border: 1px solid black; padding: 8px; text-align: left; width: 200px;"><strong>Vendor</strong></th>
            @elseif ($type_transaksi == 'barang_keluar')
                <th style="border: 1px solid black; padding: 8px; text-align: left; width: 200px;"><strong>Customer</strong></th>
            @else
                <th style="border: 1px solid black; padding: 8px; text-align: left; width: 200px;"></th>
            @endif
            <td style="border: 1px solid black; padding: 8px; text-align: center; width: 100px"><strong>Dibuat Oleh:</strong></td>
        </tr>
    </thead>
    <tbody>
        @if ($transaksi->isEmpty())
            <tr>
                <td colspan="{{ $type_transaksi == 'barang_keluar' ? 5 : 4 }}" style="border: 1px solid black; text-align: center; padding: 8px;" class="text-center">Data transaksi tidak ditemukan untuk periode yang dipilih.</td>
            </tr>
        @else
            @foreach ($transaksi as $detailitem)
                <!-- Display invoice information -->
                <tr>
                    @if ($type_transaksi == 'barang_keluar')
                        <td style="border: 1px solid black; padding: 8px;">{{ $detailitem->po_number }}</td>
                    @endif
                    <td style="border: 1px solid black; padding: 8px;">
                        @if ($type_transaksi == 'barang_masuk')
                            {{ $detailitem->purchaseOrder->invoice_number ?? 'N/A' }}
                        @else
                            {{ $detailitem->invoice_number }}
                        @endif
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $detailitem->created_at->translatedFormat('d M Y') }}</td>
                    <td style="border: 1px solid black; padding: 8px;">
                        @if ($type_transaksi == 'barang_masuk')
                            {{ $detailitem->purchaseOrder->vendor ?? '-' }}
                        @elseif ($type_transaksi == 'barang_keluar')
                            {{ $detailitem->customer->name ?? '-' }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;">   
                        {{ $detailitem->user->name ?? 'N/A' }}
                    </td>
                </tr>
                
                <!-- Additional header row for part number and description -->
                <tr>
                    @if ($type_transaksi == 'barang_keluar')
                        <td style="border: 1px solid black; padding: 8px;"></td>
                    @endif
                    <td style="border: 1px solid black; padding: 8px;"><strong>Part Number</strong></td>
                    <td style="border: 1px solid black; padding: 8px;" colspan="2"><strong>Description</strong></td>
                    <td style="border: 1px solid black; padding: 8px;"><strong>Quantity</strong></td>
                </tr>

                <!-- Display each item under the current invoice -->
                @foreach ($detailitem->details as $item)
                    <tr>
                        @if ($type_transaksi == 'barang_keluar')
                            <td style="border: 1px solid black; padding: 8px;"></td>
                        @endif
                        <td style="border: 1px solid black; padding: 8px;">{{ $item->barang->part_number ?? '-' }}</td>
                        <td style="border: 1px solid black; padding: 8px;" colspan="2">{{ $item->barang->deskripsi ?? '-' }}</td>
                        <td style="border: 1px solid black; padding: 8px;" class="text-center">{{ $item->qty }}</td>
                    </tr>
                @endforeach

                <!-- Display Total per invoice -->
                <tr>
                    <td style="border: 1px solid black; padding: 8px;" colspan="{{ $type_transaksi == 'barang_keluar' ? 4 : 3 }}" class="text-right"><strong>Total</strong></td>
                    <td style="border: 1px solid black; padding: 8px;" class="text-center"><strong>{{ $detailitem->details->sum(fn($item) => $item->qty) }}</strong></td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td style="border: 1px solid black; padding: 8px;" colspan="{{ $type_transaksi == 'barang_keluar' ? 4 : 3 }}" class="text-right"><strong>Sub Total</strong></td>
            <td style="border: 1px solid black; padding: 8px;" class="text-center"><strong>{{ $transaksi->flatMap(fn($item) => $item->details)->sum('qty') }}</strong></td>
        </tr>
    </tfoot>
</table>
