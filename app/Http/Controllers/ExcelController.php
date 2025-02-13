<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelUploadRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{
    public function store(ExcelUploadRequest $request)
    {
        $file = $request->file('excel1');

        // Simpan file ke storage public
        $filePath = $file->store('excel', 'public');

        return response()->json(['file_path' => $filePath]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel1' => 'required|file|mimes:xlsx',
            'excel2' => 'required|file|mimes:xlsx',
        ]);

        $excel1Path = $request->file('excel1')->store('excel');
        $excel2Path = $request->file('excel2')->store('excel');

        $file1 = storage_path('app/' . $excel1Path);
        $file2 = storage_path('app/' . $excel2Path);

        $spreadsheet1 = IOFactory::load($file1)->getActiveSheet()->toArray();
        $spreadsheet2 = IOFactory::load($file2)->getActiveSheet()->toArray();

        if (!file_exists($file1) || !file_exists($file2)) {
            return response()->json([
                'error' => 'File tidak ditemukan!',
                'file1' => $file1,
                'file2' => $file2,
            ], 500);
        }


        $mergedData = $this->mergeExcelData($spreadsheet1, $spreadsheet2);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($mergedData as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'merged_excel.xlsx';
        $filePath = storage_path('app/public/' . $fileName);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function mergeExcelData($data1, $data2)
    {
        $header = array_shift($data1);
        array_shift($data2); // Remove header from second file

        $data1 = collect($data1)->keyBy('NIK');
        $data2 = collect($data2)->keyBy('NIK');

        foreach ($data1 as $nik => $row) {
            if (isset($data2[$nik])) {
                foreach ($row as $key => $value) {
                    if (empty($value) && !empty($data2[$nik][$key])) {
                        $data1[$nik][$key] = $data2[$nik][$key];
                    }
                }
            }
        }

        return array_merge([$header], $data1->values()->toArray());
    }
}
