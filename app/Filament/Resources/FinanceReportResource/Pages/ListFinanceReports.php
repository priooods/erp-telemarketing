<?php

namespace App\Filament\Resources\FinanceReportResource\Pages;

use App\Filament\Resources\FinanceReportResource;
use App\Filament\Widgets\StatsOverview;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
class ListFinanceReports extends ListRecords
{
    protected static string $resource = FinanceReportResource::class;
    protected static ?string $title = 'Report';
    protected ?string $heading = 'Data Report';

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->color('success')->exports([
                ExcelExport::make()->withColumns([
                    Column::make('cust_name')->getStateUsing(fn($record) => $record->lead?->customer_nama ?? '-')->heading('Customer'),
                    Column::make('cust_phone')->getStateUsing(fn($record) => $record->lead?->customer_phone ?? '-')->heading('Customer Phone'),
                    Column::make('leads_in')->getStateUsing(fn($record) => $record->lead?->lead_in ?? '-')->heading('Chat Masuk'),
                    Column::make('t_marketing_tabs_id')->heading('Marketing')
                        ->getStateUsing(fn($record) => $record->marketing?->name ?? '-'),
                    Column::make('booking_date')->heading('Booking Date'),
                    Column::make('paid')->heading('Biaya Booking'),
                ])->withFilename('Report Leads'),
            ]),
        ];
    }
}
