<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TfIdfNewResource\Pages;
use App\Filament\Resources\TfIdfNewResource\RelationManagers;
use App\Models\Evaluasi;
use App\Models\TfIdfNew;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Tables\Columns\TextColumn::make('getLabeledHasil'),

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
            'index' => Pages\ListTfIdfNews::route('/'),
            'create' => Pages\CreateTfIdfNew::route('/create'),
            'edit' => Pages\EditTfIdfNew::route('/{record}/edit'),
        ];
    }
}
