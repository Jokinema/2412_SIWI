<?php

namespace App\Filament\Resources\PreProcessingResource\Pages;

use App\Filament\Resources\PreProcessingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
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
                ->requiresConfirmation()
                ->action(function () {
                    try {

                        // Membuat proses Symfony untuk menjalankan skrip Python
                        $process = new SymfonyProcess([env('PY_DIR'), storage_path('py/tes.py')]);
//                        $process->setTimeout(60); // Opsional: menetapkan batas waktu (dalam detik)
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
                    } catch (ProcessFailedException $e) {
                        // Menangani pengecualian, misalnya memberi tahu pengguna tentang kegagalan
                        Notification::make()
                            ->title('Process failed: ' . $e->getMessage())
                            ->warning()
                            ->send();
                    }
                })
        ];
    }
}
