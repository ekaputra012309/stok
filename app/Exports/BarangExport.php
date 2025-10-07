<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $index = 0; // To track the row number

    /**
     * Get the collection of data for export.
     */
    public function collection()
    {
        return Barang::with('satuan', 'lokasi')->get(); // Fetch data with related 'satuan'
    }

    /**
     * Map the data to the desired structure.
     */
    public function map($barang): array
    {
        return [
            ++$this->index,                // Increment and include the row number
            $barang->part_number,
            $barang->deskripsi,
            $barang->stok,
            $barang->limit,
            $barang->lokasi->name ?? 'N/A',
            $barang->satuan->name ?? 'N/A', // Handle cases where 'satuan' might be null
        ];
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Part Number', 'Deskripsi', 'Stok', 'Limit', 'Satuan Name'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Column 'No' (A) width
            'B' => 20, // Column 'Part Number' (B) width
            'C' => 40, // Column 'Deskripsi' (C) width
            'D' => 20, // Column 'Stok' (D) width
            'E' => 20, // Column 'Limit' (E) width
            'F' => 15, // Column 'Satuan Name' (F) width
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center'); // Center-align column A
        $sheet->getStyle('D')->getAlignment()->setHorizontal('center'); // Center-align column D
        $sheet->getStyle('E')->getAlignment()->setHorizontal('center'); // Center-align column E
        $sheet->getStyle('F')->getAlignment()->setHorizontal('center'); // Center-align column F

        return [];
    }
}
