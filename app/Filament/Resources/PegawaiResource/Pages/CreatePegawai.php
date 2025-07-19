<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use App\Models\TUserRoleDetailTab;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;
    protected static ?string $title = 'Pegawai';
    protected ?string $heading = 'Buat Pegawai';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['m_company_tabs_id'] = auth()->user()->m_company_tabs_id;
        $data['m_status_tabs_id'] = 2;
        return $data;
    }

    protected function afterCreate(): void
    {
        TUserRoleDetailTab::create([
            'm_user_role_tabs_id' => $this->data['m_user_role_tabs_id'],
            'users_id' => $this->record->id,
        ]);
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan Data');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }
}
