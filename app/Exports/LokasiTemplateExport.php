<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LokasiTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['lokasi_name'];
    }

    public function array(): array
    {
        return [
            ['A1'], // Example row
        ];
    }
}
