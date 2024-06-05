<?php

namespace App\Filament\Resources\EvaluasiResource\Pages;

use App\Filament\Resources\EvaluasiResource;
use App\Filament\Widgets\WordCloudWidget;
use App\Models\Evaluasi;
use App\Models\PreProcessing;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process as SymfonyProcess;

class ListEvaluasis extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = EvaluasiResource::class;

//    public function getColumns(): int | string | array {
//        return 6;
//    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Run Evaluasi')
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


                        $outJson = "py/sentiment_out.csv";

                        // Membuat proses Symfony untuk menjalankan skrip Python
                        $process = new SymfonyProcess([
                            env('PY_DIR'),
                            storage_path('py/SentimentAnalyst.py'),
                            '--sentimentDataset', $sentimentDataset,
                            '--kataPenguat', $kataPenguat,
                            '--tweetDataset', $csvFilePath,
                            '--outDataset', storage_path($outJson),
                            '--appPath', storage_path()
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
                            $jsonFilePath = storage_path("py/sentiment_out.json");
                            $cleanedData = json_decode(file_get_contents($jsonFilePath), true);

// Insert cleaned data into pre_processings table
                            foreach ($cleanedData as $data) {
                                $preProcessing = PreProcessing::where('datasets_id', $data['id'])->first();

                                if ($preProcessing) {
                                    // Insert into the evaluasi table
                                    Evaluasi::create([
                                        'pre_processings_id' => $preProcessing->id,
                                        'hasil' => $data['sentiment_result'],
                                    ]);
                                }
//                                DB::table('evaluasi')->insert([
//
//                                ]);
                            }
                        }


                    } catch (ProcessFailedException $e) {
                        // Menangani pengecualian, misalnya memberi tahu pengguna tentang kegagalan
//                        dd( $e->getMessage());
                        Notification::make()
                            ->title('Process failed: ' . $e->getMessage())
                            ->warning()
                            ->send();
                    }
                })
        ];
    }

    protected function getHeaderWidgets():array
    {
        return [
            EvaluasiResource\Widget\EvaluasiWidget::class
        ];
    }
}
