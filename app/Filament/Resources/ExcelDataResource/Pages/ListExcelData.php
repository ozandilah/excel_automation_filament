<?php

namespace App\Filament\Resources\ExcelDataResource\Pages;

use App\Filament\Resources\ExcelDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExcelData extends ListRecords
{
    protected static string $resource = ExcelDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
