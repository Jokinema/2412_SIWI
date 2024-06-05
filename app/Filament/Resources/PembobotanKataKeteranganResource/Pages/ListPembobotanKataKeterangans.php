<?php

namespace App\Filament\Resources\PembobotanKataKeteranganResource\Pages;

use App\Filament\Imports\PembobotanKataKeteranganImporter;
use App\Filament\Resources\PembobotanKataKeteranganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembobotanKataKeterangans extends ListRecords
{
    protected static string $resource = PembobotanKataKeteranganResource::class;
    protected static ?string $title = 'Pembobotan Kata Keterangan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New'),
            Actions\ImportAction::make()->importer(PembobotanKataKeteranganImporter::class)->label('Import'),
        ];
    }
}
