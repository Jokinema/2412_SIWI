<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use Filament\Resources\Pages\Page;
use Filament\Actions;
class Dashboard extends Page
{
    protected static string $resource = DashboardResource::class;

    protected static string $view = 'filament.resources.dashboard-resource.pages.dashboard';

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
