<?php

namespace App\Exports;

use App\Models\Lokasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class LokasiExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    private $index = 0; // To track the row number

    /**
     * Get the collection of data for export.
     */
    public function collection()
    {
        return Lokasi::all(); // Fetch data with related 'lokasi'
    }

    /**
     * Map the data to the desired structure.
     */
    public function map($lokasi): array
    {
        return [
            ++$this->index,
            $lokasi->nama_lokasi ?? 'N/A', // Handle cases where 'lokasi' might be null
        ];
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Nama Lokasi'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Column 'No' (A) width
            'B' => 20, // Column 'Part Number' (B) width
        ];
    }
}
