<?php

namespace App\Filament\Resources\PreProcessingTokenizationResource\Pages;

use App\Filament\Resources\PreProcessingTokenizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreProcessingTokenization extends EditRecord
{
    protected static string $resource = PreProcessingTokenizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
