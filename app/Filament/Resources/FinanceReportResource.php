<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceReportResource\Pages;
use App\Filament\Widgets\StatsOverview;
use App\Models\TFinanceTab;
use App\Models\TLeadTabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FinanceReportResource extends Resource
{
    protected static ?string $model = TFinanceTab::class;
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
            TextColumn::make('t_marketing_tabs_id')->label('Marketing')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->with('marketing')->whereHas('marketing', function ($a) use ($search) {
                        $a->where('name', 'like', "%{$search}%");
                    });
                })
                ->getStateUsing(fn($record) => $record->marketing?->name ?? '-'),
            TextColumn::make('booking_date')->label('Booking Date')->date()->alignment(Alignment::Center),
            TextColumn::make('paid')->label('Biaya Booking')->getStateUsing(fn($record) => 'Rp. ' . $record->paid),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class
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
