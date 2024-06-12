<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembobotanLexiconResource\Pages;
use App\Filament\Resources\PembobotanLexiconResource\RelationManagers;
use App\Models\PembobotanLexicon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembobotanLexiconResource extends Resource
{
    protected static ?string $model = PembobotanLexicon::class;

    protected static ?string $navigationGroup = 'Pembobotan/Kamus Data';
    protected static ?string $navigationLabel = 'Pembobotan/Kamus Data Lexicon';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

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
                Tables\Columns\TextColumn::make('word')->label('Kata/Kalimat'),
                Tables\Columns\TextColumn::make('weight')->label('Bobot'),
                BadgeColumn::make('labeled_bobot')
                    ->label('Hasil Prediksi (LABEL)')
                    ->getStateUsing(fn (PembobotanLexicon $record): string => $record->labeled_bobot)
                    ->colors([
                        'danger' => 'negatif',
                        'warning' => 'netral',
                        'success' => 'positif',
                    ])
              ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPembobotanLexicons::route('/'),
            'create' => Pages\CreatePembobotanLexicon::route('/create'),
            'edit' => Pages\EditPembobotanLexicon::route('/{record}/edit'),
        ];
    }
}
