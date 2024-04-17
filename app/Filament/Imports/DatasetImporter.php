<?php

namespace App\Filament\Imports;

use App\Models\Dataset;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DatasetImporter extends Importer
{
    protected static ?string $model = Dataset::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('username')
                ->requiredMapping()
                ->rules(['sometimes', 'max:255']),
            ImportColumn::make('full_text')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sentiment')
                ->numeric()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Dataset
    {
        // return Datasets::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Dataset();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your datasets import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
