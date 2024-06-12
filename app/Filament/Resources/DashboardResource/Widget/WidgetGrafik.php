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

        $evaluasi =  DB::table('evaluasi')
            ->select(
                DB::raw("
                    CASE
                        WHEN hasil < 0 THEN 'negatif'
                        WHEN hasil BETWEEN 0 AND 1 THEN 'netral'
                        ELSE 'positif'
                    END as label
                "),
                DB::raw('count(*) as total')
            )
            ->groupBy('label')
            ->get();
//        dd([$evaluasi, $sentiments]);
        $confusionMatrix = [
            ['TP' => 0, 'FP' => 0, 'FN' => 0], // Positif
            ['TP' => 0, 'FP' => 0, 'FN' => 0], // Netral
            ['TP' => 0, 'FP' => 0, 'FN' => 0]  // Negatif
        ];
// Membuat Confusion Matrix
//        $confusionMatrix = [
//            ['Positif' => $sentiments[0]->total, 'Netral' => $sentiments[1]->total, 'Negatif' => $sentiments[2]->total],
//            ['Positif' => $evaluasi[0]->total, 'Netral' => $evaluasi[1]->total, 'Negatif' => $evaluasi[2]->total]
//        ];
//
//// Hitung True Positives, False Positives, False Negatives, dan True Negatives
//        $TP_positif = min($sentiments[0]->total, $evaluasi[0]->total);
//        $TP_netral = min($sentiments[1]->total, $evaluasi[1]->total);
//        $TP_negatif = min($sentiments[2]->total, $evaluasi[2]->total);
//
//        $FP_positif = $sentiments[0]->total - $TP_positif;
//        $FP_netral = $sentiments[1]->total - $TP_netral;
//        $FP_negatif = $sentiments[2]->total - $TP_negatif;
//
//        $FN_positif = $evaluasi[0]->total - $TP_positif;
//        $FN_netral = $evaluasi[1]->total - $TP_netral;
//        $FN_negatif = $evaluasi[2]->total - $TP_negatif;
//
//        $TN_positif = array_sum(array_column($confusionMatrix[1], 'total')) - ($TP_positif + $FP_positif + $FN_positif);
//        $TN_netral = array_sum(array_column($confusionMatrix[1], 'total')) - ($TP_netral + $FP_netral + $FN_netral);
//        $TN_negatif = array_sum(array_column($confusionMatrix[1], 'total')) - ($TP_negatif + $FP_negatif + $FN_negatif);
//
//// Menghitung Akurasi
//        $accuracy = ($TP_positif + $TP_netral + $TP_negatif) / array_sum(array_column($confusionMatrix[1], 'total'));
//
//// Menghitung Presisi
//        $precision_positif = $TP_positif / ($TP_positif + $FP_positif);
//        $precision_netral = $TP_netral / ($TP_netral + $FP_netral);
//        $precision_negatif = $TP_negatif / ($TP_negatif + $FP_negatif);
//
//// Menghitung Recall
//        $recall_positif = $TP_positif / ($TP_positif + $FN_positif);
//        $recall_netral = $TP_netral / ($TP_netral + $FN_netral);
//        $recall_negatif = $TP_negatif / ($TP_negatif + $FN_negatif);

//        dd([$accuracy ,[$precision_positif, $precision_netral, $precision_negatif] ]);
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
                        $evaluasi[0]->total,
                        $evaluasi[1]->total,
                        $evaluasi[2]->total,

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
