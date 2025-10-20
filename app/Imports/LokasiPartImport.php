<?php

namespace App\Imports;

use App\Models\Lokasi;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class LokasiPartImport implements ToCollection, WithHeadingRow
{
    public $errors = []; // To collect validation errors

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $rowNumber = $key + 2; // +2 because heading row starts at row 1

            // Skip example or template row
            if (isset($row['lokasi_name']) && $row['lokasi_name'] === 'A1') {
                continue;
            }

            // Validate each row
            $validator = Validator::make($row->toArray(), [
                'lokasi_name' => 'required|string|max:255|unique:lokasi,nama_lokasi',
            ], [
                'lokasi_name.required' => 'Kolom lokasi_name wajib diisi.',
                'lokasi_name.unique' => 'Lokasi "' . ($row['lokasi_name'] ?? '') . '" sudah ada.',
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'error' => $validator->errors()->first(),
                ];
                continue; // Skip invalid rows
            }

            // Create Lokasi
            Lokasi::create([
                'nama_lokasi' => $row['lokasi_name'],
                'user_id'     => auth()->id(),
            ]);
        }
    }
}
