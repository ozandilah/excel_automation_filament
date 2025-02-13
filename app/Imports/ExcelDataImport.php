<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ExcelDataImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Menghapus header jika ada
        $rows->shift();

        return $rows->map(function ($row) {
            return [
                'no' => $row[0],
                'first_name' => $row[1],
                'last_name' => $row[2],
                'full_name' => $row[3],
                'email_address' => $row[4],
                'mobile_telephone_number' => $row[5],
                'report_to' => $row[6],
                'company' => $row[7],
                'department' => $row[8],
                'nik' => $row[9],
                'employee_type' => $row[10],
                'street_address' => $row[11],
                'city' => $row[12],
                'state_province' => $row[13], // Bisa kosong
                'zip' => $row[14], // Bisa kosong
            ];
        });
    }
}
