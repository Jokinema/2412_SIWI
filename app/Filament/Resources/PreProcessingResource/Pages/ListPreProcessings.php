<?php

namespace App\Filament\Resources\PreProcessingResource\Pages;

use App\Filament\Resources\PreProcessingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Process as SymfonyProcess;
//use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ListPreProcessings extends ListRecords
{
    protected static string $resource = PreProcessingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Run Pre-Processing')
                ->color('success')
                ->icon('heroicon-m-play')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        // Query data from datasets table
                        $datasets = DB::table('datasets')->select('id', 'full_text')->get();

// Create CSV file with the data
                        $csvFilePath = storage_path('app/datasets.csv');
                        $csvFile = fopen($csvFilePath, 'w');
                        foreach ($datasets as $dataset) {
                            fputcsv($csvFile, [$dataset->id, $dataset->full_text]);
                        }
                        fclose($csvFile);

                        $outJson = "py/pre_processing_cleaning_out.json";

                        // Membuat proses Symfony untuk menjalankan skrip Python
                        $process = new SymfonyProcess([
                            env('PY_DIR'),
                            storage_path('py/pre_processing_cleaning.py'),
                            '--input_csv', $csvFilePath,
                            '--output_json',    storage_path($outJson)
                            ]);
                        $process->setTimeout(60); // Opsional: menetapkan batas waktu (dalam detik)
                        $process->run();

                        // Memeriksa apakah proses berhasil
                        if (!$process->isSuccessful()) {
                            throw new ProcessFailedException($process);
                        }

                        // Mendapatkan output standar dan output kesalahan (jika ada)
                        $output = $process->getOutput();
                        $errorOutput = $process->getErrorOutput();

                        // Menggunakan output dalam notifikasi
                        Notification::make()
                            ->title((string) $output)
                            ->success()
                            ->send();

                        // Opsional: log output kesalahan jika ada
                        if (!empty($errorOutput)) {
                            // Log atau tangani output kesalahan
                            // Menggunakan output dalam notifikasi
                            Notification::make()
                                ->title((string) $errorOutput)
                                ->warning()
                                ->send();
                        }
                        else { // jika tidak ada kesalhan
                            $jsonFilePath = storage_path($outJson);
                            $cleanedData = json_decode(file_get_contents($jsonFilePath), true);

// Insert cleaned data into pre_processings table
                            foreach ($cleanedData as $data) {
                                DB::table('pre_processings')->insert([
                                    'datasets_id' => $data['id'],
                                    'cleaned' => $data['cleaned_text'],
                                    'case_folded' => '',
                                    'tokenized' => '',
                                    'original' => '',
                                ]);
                            }
                        }


                    } catch (ProcessFailedException $e) {
                        // Menangani pengecualian, misalnya memberi tahu pengguna tentang kegagalan
                        dd( $e->getMessage());
                        Notification::make()
                            ->title('Process failed: ' . $e->getMessage())
                            ->warning()
                            ->send();
                    }
                })
        ];
    }
}
