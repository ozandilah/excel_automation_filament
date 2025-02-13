<?php

namespace App\Filament\Resources\ExcelDataResource\Pages;

use App\Filament\Resources\ExcelDataResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;

class CreateExcelData extends CreateRecord
{
    protected static string $resource = ExcelDataResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Ambil path file dari JSON yang disimpan di database
        $excel1Array = json_decode($record->excel_1, true);
        $excel2Array = json_decode($record->excel_2, true);

        if (!$excel1Array || !$excel2Array) {
            throw new \Exception('File Excel belum diunggah dengan benar.');
        }

        // Ambil path file sebenarnya dari JSON
        $excel1Path = reset($excel1Array);
        $excel2Path = reset($excel2Array);

        // Debugging: Catat log path file
        Log::info('Cek file Excel:', [
            'excel_1' => $excel1Path,
            'excel_2' => $excel2Path,
            'exists_1' => Storage::disk('public')->exists($excel1Path),
            'exists_2' => Storage::disk('public')->exists($excel2Path),
        ]);

        // Pastikan file benar-benar ada di storage
        if (!Storage::disk('public')->exists($excel1Path) || !Storage::disk('public')->exists($excel2Path)) {
            throw new \Exception('File Excel tidak ditemukan di penyimpanan.');
        }

        // Ambil path file dari storage
        $file1Path = Storage::disk('public')->path($excel1Path);
        $file2Path = Storage::disk('public')->path($excel2Path);

        // Load file dengan PhpSpreadsheet
        $spreadsheet1 = IOFactory::load($file1Path);
        $spreadsheet2 = IOFactory::load($file2Path);

        $sheet1 = $spreadsheet1->getActiveSheet();
        $sheet2 = $spreadsheet2->getActiveSheet();

        // Gabungkan data dari sheet kedua ke sheet pertama
        $lastRow = $sheet1->getHighestRow();
        $data2 = $sheet2->toArray();

        foreach ($data2 as $rowIndex => $rowData) {
            foreach ($rowData as $colIndex => $cellValue) {
                $sheet1->setCellValueByColumnAndRow($colIndex + 1, $lastRow + $rowIndex + 1, $cellValue);
            }
        }

        // Simpan file hasil gabungan
        $newFilePath = 'uploads/merged_' . time() . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet1, 'Xlsx');
        $writer->save(Storage::disk('public')->path($newFilePath));

        // Simpan path file gabungan di database
        $record->update(['merged_file' => $newFilePath]);

        // Catat log sukses
        Log::info('File berhasil digabung dan disimpan:', [
            'merged_file' => $newFilePath,
        ]);
    }
}
