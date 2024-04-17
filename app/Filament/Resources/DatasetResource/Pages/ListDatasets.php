<?php

namespace App\Filament\Resources\DatasetResource\Pages;

use App\Filament\Imports\DatasetImporter;
use App\Filament\Resources\DatasetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatasets extends ListRecords
{
    protected static string $resource = DatasetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()->importer(DatasetImporter::class),
        ];
    }
}
