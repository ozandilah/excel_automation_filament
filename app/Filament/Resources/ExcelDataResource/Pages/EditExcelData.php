<?php

namespace App\Filament\Resources\ExcelDataResource\Pages;

use App\Filament\Resources\ExcelDataResource;
use App\Http\Controllers\ExcelProcessingController;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditExcelData extends EditRecord
{
    protected static string $resource = ExcelDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('processExcel')
                ->label('Gabungkan & Unduh Excel')
                ->color('success')
                ->action(fn() => $this->processExcel()),
        ];
    }

    public function processExcel()
    {
        $controller = app(ExcelProcessingController::class);
        return $controller->processExcel($this->record->id);
    }
}
