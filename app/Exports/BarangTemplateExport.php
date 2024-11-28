<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['deskripsi', 'part_number', 'satuan_name'];
    }

    public function array(): array
    {
        return [
            ['Example Description', 'EX-12345', 'Kilogram'], // Example row
        ];
    }
}
