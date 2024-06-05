<?php

namespace App\Filament\Resources\PembobotanLexiconResource\Pages;

use App\Filament\Imports\PembobotanLexiconImporter;
use App\Filament\Resources\PembobotanLexiconResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembobotanLexicons extends ListRecords
{
    protected static string $resource = PembobotanLexiconResource::class;
    protected static ?string $title =  'Pembobotan Lexicon';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New'),
            Actions\ImportAction::make()->importer(PembobotanLexiconImporter::class)->label('Import'),
        ];
    }
}
