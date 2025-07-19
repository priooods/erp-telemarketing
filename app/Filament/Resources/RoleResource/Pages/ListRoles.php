<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;
    protected static ?string $title = 'Role';
    protected ?string $heading = 'Data Role';
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()->label('Tambah Role'),
        ];
    }
}
