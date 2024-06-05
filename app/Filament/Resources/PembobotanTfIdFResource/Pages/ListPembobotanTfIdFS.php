<?php

namespace App\Filament\Resources\PembobotanTfIdFResource\Pages;

use App\Filament\Resources\PembobotanTfIdFResource;
use App\Models\PreProcessing;
use App\Models\TfidfResult;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process as SymfonyProcess;

class ListPembobotanTfIdFS extends ListRecords
{
    protected static string $resource = PembobotanTfIdFResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Run Tokenisasi')
                ->color('success')
                ->icon('heroicon-m-play')
                ->requiresConfirmation()
                ->action(function () {
                    try {

                        $tweets = PreProcessing::all(['id','datasets_id', 'tokenized'])->toArray();

                        $tokenizedTweets = array_map(function ($tweet) {
                            $tweet['tokenized'] = str_replace("'", '"', $tweet['tokenized']);
                            return json_decode($tweet['tokenized'], true);
                        }, $tweets);

                        $tf = [];
                        $allTokens = [];

                        foreach ($tokenizedTweets as $index => $tweet) {
                            if (is_null($tweet))   dd([$tweet, $index, $tokenizedTweets]);

//                            echo ($tweet);
                            $tf[$index] = array_count_values($tweet);
                            $allTokens = array_merge($allTokens, $tweet);
                        }

                        $df = array_count_values($allTokens);
                        $numDocuments = count($tokenizedTweets);
                        $tfidf = [];

                        foreach ($tf as $docIndex => $termFrequencies) {
                            foreach ($termFrequencies as $term => $count) {
                                $tfidf[] = [
                                    'id_data' => $tweets[$docIndex]['datasets_id'],
                                    'kata' => $term,
                                    'nilai_tfidf' => $count * log($numDocuments / (1 + $df[$term]))
                                ];
                                TfidfResult::create([
                                    'datasets_id' => $tweets[$docIndex]['datasets_id'],
                                    'pre_processings_id' =>$tweets[$docIndex]['datasets_id'],
                                    'term' => $term,
                                    'tfidf' => $count * log($numDocuments / (1 + $df[$term]))
                                ]);
                            }
                        }
//                        dd($tfidf);

//                        dd($tfidf);
//                        foreach ($tfidf as $docId => $terms) {
//                            foreach ($terms as $term => $value) {
//                                TfidfResult::create([
//                                    'datasets_id' => $docId,
//                                    'pre_processings_id' => $docId,
//                                    'term' => $term,
//                                    'tfidf' => $value
//                                ]);
//
//                            }
//                        }
                        // Save TF-IDF results
//                        foreach ($tweets as $index => $tweet) {
//                            foreach ($tokenizedTweets[$index] as $term => $tfidf) {
//                                TfidfResult::create([
//                                    'pre_processings_id' => $tweet['datasets_id'],
//                                    'term' => $term,
//                                    'tfidf' => $tfidf
//                                ]);
//                            }
//                        }

                    } catch (ProcessFailedException $e) {
                        // Menangani pengecualian, misalnya memberi tahu pengguna tentang kegagalan
                        dd( $e->getMessage());
                        Notification::make()
                            ->title('Process failed: ' . $e->getMessage())
                            ->warning()
                            ->send();
                    }
                })
        ];
    }
}
