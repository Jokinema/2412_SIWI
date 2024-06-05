<?php

namespace App\Filament\Resources\PembobotanTfIdFResource\Pages;

use App\Filament\Resources\PembobotanTfIdFResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembobotanTfIdF extends EditRecord
{
    protected static string $resource = PembobotanTfIdFResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
