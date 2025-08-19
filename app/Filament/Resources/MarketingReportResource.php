<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingReportResource\Pages;
use App\Filament\Resources\MarketingReportResource\RelationManagers;
use App\Models\MarketingReport;
use App\Models\TFinanceTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketingReportResource extends Resource
{
    protected static ?string $model = TFinanceTab::class;
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $breadcrumb = "Laporan Penjualan";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Detail Booking')
                ->schema([
                    TextEntry::make('created_by')->label('Dibuat Oleh')
                        ->getStateUsing(fn($record) => $record->createdby?->name ?? '-'),
                    TextEntry::make('t_marketing_tabs_id')->label('Marketing Closing')
                        ->getStateUsing(fn($record) => $record->marketing?->name ?? '-'),
                    TextEntry::make('m_unit_tabs_id')
                        ->label('Unit')
                        ->getStateUsing(fn($record) => $record->unit?->title ?? '-'),
                    TextEntry::make('booking_date')
                        ->label('Booking Date')
                        ->date(),
                    TextEntry::make('booking_date')
                        ->label('Biaya Booking')->getStateUsing(fn($record) => 'Rp. ' . $record->paid),
                ])->columns(2),
            Section::make('Detail Leads')
                ->schema([
                    RepeatableEntry::make('lead.detail')
                        ->schema([
                            TextEntry::make('status.title')->badge(),
                            TextEntry::make('marketing.name')->label('Marketing'),
                            TextEntry::make('visit_date')->date()->label('Tanggal Visit'),
                            TextEntry::make('description'),
                        ])
                        ->columns(3)
                ]),
        ]);
    }

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
            ->query(
            TFinanceTab::orderBy('id', 'desc')
            )
            ->emptyStateHeading('laporan Penjualan Kosong')
            ->emptyStateDescription('Tidak ada informasi laporan Penjualan')
            ->columns([
                TextColumn::make('t_marketing_tabs_id')->label('Marketing')
                    ->getStateUsing(fn($record) => $record->marketing?->name ?? '-')
                    ->description(fn(TFinanceTab $record): string => 'SPV : ' . $record->marketing?->atasan?->name),
                TextColumn::make('m_unit_tabs_id')->label('Unit')
                    ->getStateUsing(fn($record) => $record->unit?->title ?? '-')
                    ->description(fn(TFinanceTab $record): string => $record->lead?->project?->title),
                TextColumn::make('booking_date')->label('Booking Date')->date()->alignment(Alignment::Center),
                TextColumn::make('t_lead_tabs_id')->label('Customer')
                    ->getStateUsing(fn($record) => $record->lead?->customer_nama ?? '-')
                    ->description(fn(TFinanceTab $record): string => $record->lead?->customer_phone),
                TextColumn::make('paid')->label('Biaya Booking')->getStateUsing(fn($record) => 'Rp. '.$record->paid),
                TextColumn::make('m_status_tabs_id')->badge()->label('Status')->alignment(Alignment::Center)
                    ->getStateUsing(fn($record) => $record->status ? $record->status->title : '-')
                    ->color(fn(string $state): string => match ($state) {
                        'BOOKING' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\ViewAction::make()->label('Detail')->modalHeading('Detail Perjualan')
            ])
            ->bulkActions([
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
            'index' => Pages\ListMarketingReports::route('/'),
            'create' => Pages\CreateMarketingReport::route('/create'),
            'edit' => Pages\EditMarketingReport::route('/{record}/edit'),
        ];
    }
}
