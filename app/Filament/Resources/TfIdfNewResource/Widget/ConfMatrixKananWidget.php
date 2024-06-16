<?php

namespace App\Filament\Resources\TfIdfNewResource\Widget;

use App\Models\Evaluasi; // Replace with your actual model
//use App\Models\Order;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ConfMatrixKananWidget  extends BaseWidget
{
    protected static ?string $heading = 'Dashboard Chart';
    protected int | string | array $columnSpan = 'full';

    public function render(): \Illuminate\Contracts\View\View
    {
        $data = DB::table('evaluasi')
            ->leftJoin('pre_processings', 'evaluasi.pre_processings_id', '=', 'pre_processings.id')
            ->leftJoin('datasets', 'pre_processings.datasets_id', '=', 'datasets.id')
            ->select('datasets.sentiment', 'evaluasi.hasil')
            ->get();

        $truePositive = $trueNegative = $falsePositive = $falseNegative = 0;

        foreach ($data as $entry) {
            $actual = $entry->sentiment;

            // Determine the predicted sentiment based on the value of hasil
            $predicted = null;
            if ($entry->hasil < 0) {
                $predicted = 'negatif';
            } elseif ($entry->hasil >= 0 && $entry->hasil <= 1) {
                $predicted = 'netral';
            } else {
                $predicted = 'positif';
            }

            // Update the counts for true positives, true negatives, false positives, and false negatives
            if ($actual == 'positif' && $predicted == 'positif') {
                $truePositive++;
            } elseif ($actual == 'negatif' && $predicted == 'negatif') {
                $trueNegative++;
            } elseif ($actual == 'positif' && $predicted == 'negatif') {
                $falseNegative++;
            } elseif ($actual == 'negatif' && $predicted == 'positif') {
                $falsePositive++;
            }
        }

        // Calculate metrics
        $total = $truePositive + $trueNegative + $falsePositive + $falseNegative;
        $accuracy = ($total > 0) ? ($truePositive + $trueNegative) / $total * 100 : 0;
        $precision = ($truePositive + $falsePositive > 0) ? $truePositive / ($truePositive + $falsePositive) * 100 : 0;
        $recall = ($truePositive + $falseNegative > 0) ? $truePositive / ($truePositive + $falseNegative) * 100 : 0;
        $tableData = [
            ['metric' => 'Akurasi', 'value' => round($accuracy, 2)],
            ['metric' => 'Presisi', 'value' => round($precision, 2)],
            ['metric' => 'Recall', 'value' => round($recall, 2)],
        ];
        return view('filament.resources.tf-idf-new-resource.widget.confmatrix-kanan-table', [
            'metrics' => $tableData,
        ]);
    }

    /**
     * @return Builder
     */
    protected function getTableQuery(): Builder
    {
        return Evaluasi::query();
    }
}
