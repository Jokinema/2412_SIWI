<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDashboards extends ListRecords
{
    protected static string $resource = DashboardResource::class;
    protected static ?string $title = 'Dashboard';
    protected int | string | array $columnSpan = 'full';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets():array
    {
        return [
            DashboardResource\Widget\WidgetGrafik::class
        ];
    }
}
