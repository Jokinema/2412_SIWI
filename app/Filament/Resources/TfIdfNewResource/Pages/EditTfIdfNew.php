<?php

namespace App\Filament\Resources\TfIdfNewResource\Pages;

use App\Filament\Resources\TfIdfNewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTfIdfNew extends EditRecord
{
    protected static string $resource = TfIdfNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
