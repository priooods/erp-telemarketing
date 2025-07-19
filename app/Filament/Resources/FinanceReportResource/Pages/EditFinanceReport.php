<?php

namespace App\Filament\Resources\FinanceReportResource\Pages;

use App\Filament\Resources\FinanceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinanceReport extends EditRecord
{
    protected static string $resource = FinanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
