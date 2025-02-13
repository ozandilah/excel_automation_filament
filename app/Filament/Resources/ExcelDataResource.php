<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExcelDataResource\Pages;
use App\Filament\Resources\ExcelDataResource\RelationManagers;
use App\Models\ExcelData;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExcelDataResource extends Resource
{
    protected static ?string $model = ExcelData::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('excel_1')
                    ->disk('public') // Simpan di disk 'public'
                    ->directory('uploads') // Masukkan ke folder 'uploads'
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
                    ->storeFileNamesIn('excel_1') // Pastikan ini menyimpan nama file
                    ->dehydrated(), // Wajib agar path tersimpan di database

                FileUpload::make('excel_2')
                    ->disk('public')
                    ->directory('uploads')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
                    ->storeFileNamesIn('excel_2')
                    ->dehydrated(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('excel_1')
                    ->label('Excel File 1')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('excel_2')
                    ->label('Excel File 2')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExcelData::route('/'),
            'create' => Pages\CreateExcelData::route('/create'),
            'edit' => Pages\EditExcelData::route('/{record}/edit'),
        ];
    }
}
