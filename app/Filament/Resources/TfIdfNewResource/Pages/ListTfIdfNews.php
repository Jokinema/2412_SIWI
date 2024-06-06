<?php

namespace App\Filament\Resources\TfIdfNewResource\Pages;

use App\Filament\Resources\TfIdfNewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTfIdfNews extends ListRecords
{
    protected static string $resource = TfIdfNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
