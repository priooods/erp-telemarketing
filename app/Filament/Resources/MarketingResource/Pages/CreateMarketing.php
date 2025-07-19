<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketing extends CreateRecord
{
    protected static string $resource = MarketingResource::class;
    protected static ?string $title = 'Marketing';
    protected ?string $heading = 'Buat Marketing';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['m_status_tabs_id'] = 2;
        return $data;
    }
}
