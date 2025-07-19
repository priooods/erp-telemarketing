<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceReportResource\Pages;
use App\Filament\Resources\FinanceReportResource\RelationManagers;
use App\Models\FinanceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FinanceReportResource extends Resource
{
    protected static ?string $model = FinanceReport::class;
    protected static ?string $navigationLabel = 'Report';
    protected static ?string $breadcrumb = "Report";
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
                //
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
            'index' => Pages\ListFinanceReports::route('/'),
            'create' => Pages\CreateFinanceReport::route('/create'),
            'edit' => Pages\EditFinanceReport::route('/{record}/edit'),
        ];
    }
}
