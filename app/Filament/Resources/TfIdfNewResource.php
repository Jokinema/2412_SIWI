<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TfIdfNewResource\Pages;
use App\Filament\Resources\TfIdfNewResource\RelationManagers;
use App\Models\Dataset;
use App\Models\Evaluasi;
use App\Models\TfIdfNew;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

enum Sentiment: string
{
    case Positif = 'positif';
    case Netral = 'netral';
    case Negatif = 'negatif';
}
class TfIdfNewResource extends Resource
{
    protected static ?string $model = Evaluasi::class;
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'TF-IDF';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('preprocess.cleaned')->label('Text'),

                BadgeColumn::make('preprocess.dataset.sentiment')
                    ->label('Sentiment Manual')
                    ->getStateUsing(function (Evaluasi  $record): string {
                        // Join tabel evaluasi, pre_processings, dan datasets
                        $sentiment = DB::table('evaluasi')
                            ->leftJoin('pre_processings', 'evaluasi.pre_processings_id', '=', 'pre_processings.id')
                            ->leftJoin('datasets', 'pre_processings.datasets_id', '=', 'datasets.id')
                            ->where('evaluasi.id', $record->id)
                            ->value('datasets.sentiment');

                      return Sentiment::from($sentiment)->value;
                    })
                    ->colors([
                        'danger' => Sentiment::Negatif->value,
                        'warning' => Sentiment::Netral->value,
                        'success' => Sentiment::Positif->value,
                    ]),
                BadgeColumn::make('labeled_hasil')
                    ->label('Sentiment TF')
                    ->getStateUsing(fn (Evaluasi $record): string => $record->labeled_hasil)
                    ->colors([
                        'danger' => 'negatif',
                        'warning' => 'netral',
                        'success' => 'positif',
                    ]),
                BadgeColumn::make('tfidf')
                    ->label('Sentiment TF-IDF')
                    ->getStateUsing(fn (Evaluasi $record): string => $record->labeled_hasil)
                    ->colors([
                        'danger' => 'negatif',
                        'warning' => 'netral',
                        'success' => 'positif',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTfIdfNews::route('/'),
            'create' => Pages\CreateTfIdfNew::route('/create'),
            'edit' => Pages\EditTfIdfNew::route('/{record}/edit'),
        ];
    }
}
