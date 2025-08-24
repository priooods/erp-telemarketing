<?php

namespace App\Filament\Resources\LeadsResource\Pages;

use App\Filament\Resources\LeadsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadsResource::class;
    protected static ?string $title = 'Leads';
    protected ?string $heading = 'Data Leads';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Leads')
                ->visible(auth()->user()->role_detail->role->id == 1 || auth()->user()->role_detail->role->id == 2),
        ];
    }
}
