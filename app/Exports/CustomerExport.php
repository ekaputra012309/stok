<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    private $index = 0; // To track the row number

    /**
     * Get the collection of data for export.
     */
    public function collection()
    {
        return Customer::all(); // Fetch data with related 'customer'
    }

    /**
     * Map the data to the desired structure.
     */
    public function map($customer): array
    {
        return [
            ++$this->index,
            $customer->name ?? 'N/A', // Handle cases where 'customer' might be null
        ];
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return ['No', 'Nama Customer'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Column 'No' (A) width
            'B' => 30, // Column 'Part Number' (B) width
        ];
    }
}
