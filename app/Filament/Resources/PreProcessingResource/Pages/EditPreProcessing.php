<?php

namespace App\Filament\Resources\PreProcessingResource\Pages;

use App\Filament\Resources\PreProcessingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreProcessing extends EditRecord
{
    protected static string $resource = PreProcessingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
