<?php

namespace App\Filament\Resources\TrainingDataResource\Pages;

use App\Filament\Resources\TrainingDataResource;
use App\Models\Dataset;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTrainingData extends ListRecords
{
    protected static string $resource = TrainingDataResource::class;

    protected static ?string $title = "Data Latih";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $modelGet = $this->getModel()::get();

        $tabs = [
            'all' => Tab::make('All')->badge($this->getModel()::count()),
            '70' => Tab::make('70%')->badge(ceil((int)$this->getModel()::count() * 0.7) ) ,
            '80' => Tab::make('80%')->badge(ceil((int)$this->getModel()::count() * 0.8)),
            '90' => Tab::make('90%')->badge(ceil((int)$this->getModel()::count() * 0.9))
        ];



        return $tabs;
    }
}
