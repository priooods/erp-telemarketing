<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;
    protected static ?string $title = 'Unit';
    protected ?string $heading = 'Edit Unit';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Unit'),
        ];
    }
}
