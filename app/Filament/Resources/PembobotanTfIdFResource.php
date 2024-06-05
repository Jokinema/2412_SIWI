<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembobotanTfIdFResource\Pages;
use App\Filament\Resources\PembobotanTfIdFResource\RelationManagers;
use App\Models\PembobotanTfIdF;
use App\Models\TfidfResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembobotanTfIdFResource extends Resource
{
    protected static ?string $model = TfidfResult::class;
    protected static ?string $navigationGroup = 'Pembobotan';
    protected static ?string $navigationLabel = 'Pembobotan TF-IDF';
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
                Tables\Columns\TextColumn::make('datasets_id')->label('ID DATA'),
                Tables\Columns\TextColumn::make('term')->label('Term (Kata)'),
                Tables\Columns\TextColumn::make('tfidf')->label('Nilai TF-IDF'),
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
            'index' => Pages\ListPembobotanTfIdFS::route('/'),
            'create' => Pages\CreatePembobotanTfIdF::route('/create'),
            'edit' => Pages\EditPembobotanTfIdF::route('/{record}/edit'),
        ];
    }
}
