<?php

namespace App\Filament\Resources\ValidationDataResource\Pages;

use App\Filament\Resources\ValidationDataResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListValidationData extends ListRecords
{
    protected static string $resource = ValidationDataResource::class;
    protected static ?string $title = "Validation Data";
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
            '70' => Tab::make('30%')->badge(ceil((int)$this->getModel()::count() * 0.3) ) ,
            '80' => Tab::make('20%')->badge(ceil((int)$this->getModel()::count() * 0.2)),
            '90' => Tab::make('10%')->badge(ceil((int)$this->getModel()::count() * 0.1))
        ];



        return $tabs;
    }
}
