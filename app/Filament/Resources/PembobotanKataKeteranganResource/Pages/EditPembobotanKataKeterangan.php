<?php

namespace App\Filament\Resources\PembobotanKataKeteranganResource\Pages;

use App\Filament\Resources\PembobotanKataKeteranganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembobotanKataKeterangan extends EditRecord
{
    protected static string $resource = PembobotanKataKeteranganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
