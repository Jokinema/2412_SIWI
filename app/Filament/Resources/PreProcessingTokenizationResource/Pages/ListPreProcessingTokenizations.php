<?php

namespace App\Filament\Resources\PreProcessingTokenizationResource\Pages;

use App\Filament\Resources\PreProcessingTokenizationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process as SymfonyProcess;

class ListPreProcessingTokenizations extends ListRecords
{
    protected static string $resource = PreProcessingTokenizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Run Tokenisasi')
                ->color('success')
                ->icon('heroicon-m-play')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        // Query data from datasets table
                        $sentiments = DB::table('bobot_lexicon')->select('word', 'weight')->get();
                        $kataketerangan = DB::table('bobot_kata_keterangan')->select('word', 'weight')->get();
                        $datasets = DB::table('pre_processings')->select('datasets_id', 'cleaned')->get();

                        // Create CSV file with the data
                        $csvFilePath = storage_path('app/pre_process.csv');
                        $sentimentDataset = storage_path('app/sentiments.csv');
                        $kataPenguat = storage_path('app/keterangan.csv');

                        $csvFile = fopen($csvFilePath, 'w');
                        foreach ($datasets as $dataset) {
                            fputcsv($csvFile, [$dataset->datasets_id, $dataset->cleaned]);
                        }
                        fclose($csvFile);


                        $sentimentFile = fopen($sentimentDataset, 'w');
                        foreach ($sentiments as $sentiment) {
                            fputcsv($sentimentFile, [$sentiment->word, $sentiment->weight]);
                        }
                        fclose($sentimentFile);


                        $keteranganFile = fopen($kataPenguat, 'w');
                        foreach ($kataketerangan as $keterangan) {
                            fputcsv($keteranganFile, [$keterangan->word, $keterangan->weight]);
                        }
                        fclose($keteranganFile);


                        $outJson = "py/pre_processing_token_out.csv";

                        // Membuat proses Symfony untuk menjalankan skrip Python
                        $process = new SymfonyProcess([
                            env('PY_DIR'),
                            storage_path('py/SentimentTokenisasi.py'),
                            '--sentimentDataset', $sentimentDataset,
                            '--kataPenguat', $kataPenguat,
                            '--tweetDataset', $csvFilePath,
                            '--outDataset', storage_path($outJson)
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
                            ->title((string)$output)
                            ->success()
                            ->send();

                        // Opsional: log output kesalahan jika ada
                        if (!empty($errorOutput)) {
                            // Log atau tangani output kesalahan
                            // Menggunakan output dalam notifikasi
                            Notification::make()
                                ->title((string)$errorOutput)
                                ->warning()
                                ->send();
                        } else { // jika tidak ada kesalhan
                            $jsonFilePath = storage_path("py/pre_processing_token_out.json");
                            $cleanedData = json_decode(file_get_contents($jsonFilePath), true);

// Insert cleaned data into pre_processings table
                            foreach ($cleanedData as $data) {
                                DB::table('pre_processings')->where('datasets_id',$data['id'])->update([
                                    'tokenized' => $data['token'],
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
