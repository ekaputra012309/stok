<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class BarangImport implements ToCollection, WithHeadingRow
{
    public $errors = []; // To collect validation errors

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $rowNumber = $key + 1; // Excel row number

            // Check if the row matches the example data and skip it
            if (
                $row['deskripsi'] === 'Example Description' &&
                $row['part_number'] === 'EX-12345' &&
                $row['limit'] === '10' &&
                $row['satuan_name'] === 'PCS'
            ) {
                continue; // Skip this row
            }

            // Validate the row data
            $validator = Validator::make($row->toArray(), [
                'deskripsi'   => 'required|string|max:255',
                'part_number' => [
                    'required',
                    'max:255',
                    'regex:/^[\p{L}\p{N}\p{P}\p{S}]+$/u', // Allows letters, numbers, punctuation, and symbols
                    'unique:barang,part_number'
                ],
                'satuan_name' => 'required|string|exists:satuan,name',
            ]);

            if ($validator->fails()) {
                // Format the error message with HTML (make part number and satuan_name bold)
                $errorMessage = $validator->errors()->first();

                // Replace part number and satuan name with bold text
                if (strpos($errorMessage, 'part number') !== false) {
                    $errorMessage = str_replace(
                        'The part number', 
                        'The part number <b>' . $row['part_number'] . '</b>', 
                        $errorMessage
                    );
                } 
                if (strpos($errorMessage, 'satuan name') !== false) {
                    $errorMessage = str_replace(
                        'The selected satuan name', 
                        'The selected satuan name <b>' . $row['satuan_name'] . '</b>', 
                        $errorMessage
                    );
                }

                // Add errors for the current row with HTML formatting
                $this->errors[] = [
                    'row' => $rowNumber,
                    'error' => $errorMessage,
                ];
                continue; // Skip invalid rows
            }

            // Find the Satuan by name
            $satuan = Satuan::where('name', $row['satuan_name'])->first();

            // Create Barang
            Barang::create([
                'deskripsi'   => $row['deskripsi'],
                'part_number' => $row['part_number'],
                'limit'       => $row['limit'],
                'satuan_id'   => $satuan->id,
                'user_id'     => auth()->id(), // Set the authenticated user's ID
            ]);
        }
    }

}
