<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreProcessingTokenizationResource\Pages;
use App\Filament\Resources\PreProcessingTokenizationResource\RelationManagers;
use App\Models\PreProcessing;
use App\Models\PreProcessingTokenization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreProcessingTokenizationResource extends Resource
{
    protected static ?string $model = PreProcessing::class;
    protected static ?string $navigationGroup = 'Models';
    protected static ?string $navigationLabel = 'Pre-Processing (Tokenisasi)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('tokenized'),
                Tables\Columns\TextColumn::make('cleaned')->label('Cleaned'),
                Tables\Columns\TextColumn::make('dataset.full_text')->label('Original'),
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
            'index' => Pages\ListPreProcessingTokenizations::route('/'),
            'create' => Pages\CreatePreProcessingTokenization::route('/create'),
            'edit' => Pages\EditPreProcessingTokenization::route('/{record}/edit'),
        ];
    }
}
