<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\ExcelData;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelProcessingController extends Controller
{
    public function processExcel($id)
    {
        // Ambil satu record berdasarkan ID
        $record = ExcelData::find($id);

        if (!$record) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil path file dari database
        $excel1Array = json_decode($record->excel_1, true);
        $excel2Array = json_decode($record->excel_2, true);

        if (!$excel1Array || !$excel2Array) {
            return back()->with('error', 'File tidak tersedia.');
        }

        $excel1Path = reset($excel1Array);
        $excel2Path = reset($excel2Array);

        if (!Storage::disk('public')->exists($excel1Path) || !Storage::disk('public')->exists($excel2Path)) {
            return back()->with('error', 'File tidak ditemukan di storage.');
        }

        // Load kedua file Excel
        $file1Path = Storage::disk('public')->path($excel1Path);
        $file2Path = Storage::disk('public')->path($excel2Path);

        $spreadsheet1 = IOFactory::load($file1Path);
        $spreadsheet2 = IOFactory::load($file2Path);

        $sheet1 = $spreadsheet1->getActiveSheet();
        $sheet2 = $spreadsheet2->getActiveSheet();

        // Ambil data dari sheet pertama dan kedua
        $data1 = $sheet1->toArray(null, true, true, true); // Excel 1
        $data2 = $sheet2->toArray(null, true, true, true); // Excel 2

        // Ambil header dari Excel 1
        $headers = array_shift($data1); // Mengambil baris pertama sebagai header
        array_shift($data2); // Hilangkan header di Excel 2

        // Buat array asosiatif berdasarkan NIK
        $data2Assoc = [];
        foreach ($data2 as $row) {
            $nik = trim($row['J'] ?? ''); // Kolom J = NIK
            if (!empty($nik)) {
                $data2Assoc[$nik] = $row; // Simpan data dengan NIK sebagai key
            }
        }

        // Proses pengisian data kosong di Excel 1
        foreach ($data1 as &$row1) {
            $nik1 = trim($row1['J'] ?? ''); // Kolom J = NIK

            if (!empty($nik1) && isset($data2Assoc[$nik1])) {
                // Ambil data dari Excel 2 berdasarkan NIK
                $row2 = $data2Assoc[$nik1];

                foreach ($row1 as $key => &$value) {
                    if (empty(trim($value)) && !empty(trim($row2[$key] ?? ''))) {
                        $value = $row2[$key]; // Isi nilai kosong dengan data dari Excel 2
                    }
                }
            }
        }

        // Buat spreadsheet baru untuk hasil akhir
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header ke sheet
        $sheet->fromArray([$headers], null, 'A1');

        // Tambahkan data hasil pengisian
        $rowIndex = 2;
        foreach ($data1 as $rowData) {
            $sheet->fromArray($rowData, null, "A$rowIndex");
            $rowIndex++;
        }

        // Simpan file gabungan ke storage
        $mergedFilePath = 'uploads/merged_' . $record->id . '_' . time() . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(Storage::disk('public')->path($mergedFilePath));

        // Unduh file hasil gabungan
        return response()->download(Storage::disk('public')->path($mergedFilePath))->deleteFileAfterSend(true);
    }
}
