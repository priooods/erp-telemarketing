<?php

namespace App\Filament\Resources\LeadsResource\Pages;

use App\Filament\Resources\LeadsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeads extends EditRecord
{
    protected static string $resource = LeadsResource::class;
    protected static ?string $title = 'Leads';
    protected ?string $heading = 'Edit Leads';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Leads'),
        ];
    }
}
