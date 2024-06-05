<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValidationDataResource\Pages;
use App\Filament\Resources\ValidationDataResource\RelationManagers;
use App\Models\PreProcessing;
use App\Models\ValidationData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValidationDataResource extends Resource
{
    protected static ?string $model = PreProcessing::class;

    protected static ?string $navigationGroup = 'Pemodelan';
    protected static ?string $navigationLabel = 'Data Validasi';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;
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
                Tables\Columns\TextColumn::make('dataset.username')->label('Username'),
                Tables\Columns\TextColumn::make('cleaned')->label('Cleaned Text'),
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
            'index' => Pages\ListValidationData::route('/'),
            'create' => Pages\CreateValidationData::route('/create'),
            'edit' => Pages\EditValidationData::route('/{record}/edit'),
        ];
    }
}
