<?php

namespace App\Filament\Resources\PreProcessingResource\Pages;

use App\Filament\Resources\PreProcessingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Process as SymfonyProcess;
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
                    $command = "ping 8.8.8.8";
                    $process = SymfonyProcess::fromShellCommandline($command);
                    $process->run();

                    if (!$process->isSuccessful()) {
                        throw new \RuntimeException($process->getErrorOutput());
                    }

                    $output = $process->getOutput();

                    Notification::make()
                        ->title((string) $output)
                        ->success()
                        ->send();
                })
        ];
    }
}
