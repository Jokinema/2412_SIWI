<?php

namespace App\Filament\Resources\ValidationDataResource\Pages;

use App\Filament\Resources\ValidationDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValidationData extends EditRecord
{
    protected static string $resource = ValidationDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
