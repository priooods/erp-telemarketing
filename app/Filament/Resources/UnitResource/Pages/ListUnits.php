<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnits extends ListRecords
{
    protected static string $resource = UnitResource::class;
    protected static ?string $title = 'Unit';
    protected ?string $heading = 'Data Unit';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Unit'),
        ];
    }
}
