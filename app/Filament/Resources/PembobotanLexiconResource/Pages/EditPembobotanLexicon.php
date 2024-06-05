<?php

namespace App\Filament\Resources\PembobotanLexiconResource\Pages;

use App\Filament\Resources\PembobotanLexiconResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembobotanLexicon extends EditRecord
{
    protected static string $resource = PembobotanLexiconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
