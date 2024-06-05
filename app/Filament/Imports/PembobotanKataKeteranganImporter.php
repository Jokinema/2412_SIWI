<?php

namespace App\Filament\Imports;

use App\Models\PembobotanKataKeterangan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PembobotanKataKeteranganImporter extends Importer
{
    protected static ?string $model = PembobotanKataKeterangan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('word')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('weight')
                ->requiredMapping()
                ->rules(['required']),

        ];
    }

    public function resolveRecord(): ?PembobotanKataKeterangan
    {
        // return PembobotanKataKeterangan::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PembobotanKataKeterangan();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pembobotan kata keterangan import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
