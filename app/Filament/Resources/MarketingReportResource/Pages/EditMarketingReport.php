<?php

namespace App\Filament\Resources\MarketingReportResource\Pages;

use App\Filament\Resources\MarketingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingReport extends EditRecord
{
    protected static string $resource = MarketingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
