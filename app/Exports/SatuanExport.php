<?php

namespace App\Exports;

use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SatuanExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    private $index = 0; // To track the row number

    /**
     * Get the collection of data for export.
     */
    public function collection()
    {
        return Satuan::all(); // Fetch data with related 'satuan'
    }

    /**
     * Map the data to the desired structure.
     */
    public function map($satuan): array
    {
        return [
            ++$this->index,
            $satuan->name ?? 'N/A', // Handle cases where 'satuan' might be null
        ];
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Nama Satuan'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Column 'No' (A) width
            'B' => 20, // Column 'Part Number' (B) width
        ];
    }
}
