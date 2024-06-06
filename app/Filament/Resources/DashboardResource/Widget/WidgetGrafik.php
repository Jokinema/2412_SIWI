<?php

namespace App\Filament\Resources\DashboardResource\Widget;
use Filament\Forms\Components\Placeholder;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Widgets\ChartWidget;

class WidgetGrafik extends ChartWidget
{
    protected static ?string $heading = 'Dashboard Chart';
    protected int | string | array $columnSpan = 'full';
    protected function getData(): array
    {
        $sentiments = DB::table('datasets')
            ->select('sentiment', DB::raw('count(*) as total'))
            ->groupBy('sentiment')
            ->get();

        // Dasnoard table

//        dd(  $sentiments->total);

        return [
            'datasets' => [
                [
                    'label' => 'Manual',
                    'data' => [
                        $sentiments[0]->total,
                        $sentiments[1]->total,
                        $sentiments[2]->total,
                    ],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Lexicon TF',
                    'data' => [
                       22, 33, 44
                    ],
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Lexicon TF-IDF',
                    'data' => [
                       55, 66, 77
                    ],
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Positif', 'Negatif', 'Netral'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
