<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketings extends ListRecords
{
    protected static string $resource = MarketingResource::class;
    protected static ?string $title = 'Marketing';
    protected ?string $heading = 'Data Marketing';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Marketing')
                ->visible(auth()->user()->role_detail->role->id === 1 || auth()->user()->role_detail->role->id === 2),
        ];
    }
}
