<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class MergedExcelExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'First Name',
            'Last Name',
            'Full Name',
            'Email Address',
            'Mobile Telephone Number',
            'Report To',
            'Company',
            'Department',
            'NIK',
            'Employee Type',
            'Street Address',
            'City',
            'State/Province',
            'Zip'
        ];
    }
}
