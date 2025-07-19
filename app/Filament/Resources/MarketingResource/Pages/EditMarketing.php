<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketing extends EditRecord
{
    protected static string $resource = MarketingResource::class;
    protected static ?string $title = 'Marketing';
    protected ?string $heading = 'Edit Marketing';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Marketing'),
        ];
    }
}
