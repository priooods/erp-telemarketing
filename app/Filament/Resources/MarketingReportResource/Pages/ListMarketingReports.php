<?php

namespace App\Filament\Resources\MarketingReportResource\Pages;

use App\Filament\Resources\MarketingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingReports extends ListRecords
{
    protected static string $resource = MarketingReportResource::class;
    protected static ?string $title = 'Laporan';
    protected ?string $heading = 'Data Laporan Penjualan';
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
