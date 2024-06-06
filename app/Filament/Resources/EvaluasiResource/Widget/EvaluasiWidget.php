<?php

namespace App\Filament\Resources\EvaluasiResource\Widget;
use Filament\Forms\Components\Placeholder;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class EvaluasiWidget extends BaseWidget
{
    use InteractsWithPageTable;
    protected int | string | array $columnSpan = 2;

    protected function getColumns() :int
    {
        return 2;
    }
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Word Cloud', '')
                ->value(function (): HtmlString {
                    $imageUrl = asset('wordcloud.jpg');
//                    $imageUrl = Storage::disk('public')->get('wordcloud.jpg');
                    return new HtmlString("<img src='{$imageUrl}' alt='Word Cloud'>");
                }),

            Stat::make('Chart Evaluasi', '')
                ->value(function (): HtmlString {
                    $imageUrl = Storage::url('sentiment.jpg');
                    return new HtmlString("<img src='{$imageUrl}' alt='Chart Evaluasi'>");
                }),
        ];
    }


}
