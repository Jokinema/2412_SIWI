<?php

namespace App\Filament\Imports;

use App\Models\PembobotanLexicon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PembobotanLexiconImporter extends Importer
{
    protected static ?string $model = PembobotanLexicon::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('word')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('weight')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('number_of_words')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?PembobotanLexicon
    {
        // return PembobotanLexicon::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PembobotanLexicon();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pembobotan lexicon import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
