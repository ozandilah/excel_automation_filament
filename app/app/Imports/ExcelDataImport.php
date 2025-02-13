<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ExcelDataImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $header = $rows->shift(); // Ambil header dan buang dari data
        return $rows->map(function ($row) use ($header) {
            return collect($header)->combine($row);
        });
    }
}
