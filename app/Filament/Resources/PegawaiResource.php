<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\MUserRoleTabs;
use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class PegawaiResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $breadcrumb = "Pegawai";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nama')->placeholder('Masukan Nama Pegawai')->required(),
            TextInput::make('email')->label('Email')->required(),
            Select::make('m_user_role_tabs_id')
                ->label('Pilih Role')
                ->relationship('role_detail', 'm_user_role_tabs_id')
                ->placeholder('Cari Role')
                ->options(MUserRoleTabs::whereNotIn('id', [1, 4])->where('m_status_tabs_id',3)->pluck('title', 'id'))
                ->getSearchResultsUsing(fn(string $search): array => MUserRoleTabs::whereNotIn('id', [1,4])
                    ->where('m_status_tabs_id', 3)
                    ->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                ->getOptionLabelUsing(fn($value): ?string => MUserRoleTabs::find($value)?->title)
                ->required(),
            TextInput::make('password')->label('Password Akun')
                ->password()->revealable()
                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                ->same('passwordConfirmation')
                ->placeholder('Masukan Password')
                ->dehydrated(fn(?string $state): bool => filled($state))
                ->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state('');
                }),
            TextInput::make('passwordConfirmation')->label('Confirmasi Password Akun')->password()->revealable()->placeholder('Masukan Password')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Pengguna Kosong')
            ->emptyStateDescription('Tidak ada informasi pengguna')
            ->query(
                User::where('m_company_tabs_id', auth()->user()->company->id)->with('role_detail')->whereHas('role_detail', function($a){
                    $a->with('role')->whereHas('role', function($b){
                        $b->whereNot('id',1);
                    });
                })
            )
            ->columns([
                TextColumn::make('name')->label('Pengguna')->description(fn($record): string => $record->email),
                TextColumn::make('role')->badge()->label('Role')->alignment(Alignment::Center)
                    ->getStateUsing(fn($record) => $record->role_detail?->role?->title ?? '-'),
                TextColumn::make('m_status_tabs_id')->badge()->label('Status Akun')->alignment(Alignment::Center)
                    ->getStateUsing(fn($record) => $record->status ? $record->status->title : '-')
                    ->color(fn(string $state): string => match ($state) {
                        'DRAFT' => 'gray',
                        'ACTIVE' => 'success',
                        'NOT ACTIVE' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('activated')
                        ->label('Aktifkan')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 3,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 2 && auth()->user()->role_detail->role->id)
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Akun')
                        ->modalDescription('Apakah anda ingin mengaktifkan Akun ?')
                        ->modalSubmitActionLabel('Aktifkan')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Action::make('Notactive')
                        ->label('Non Aktifkan')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 2,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 3 && auth()->user()->role_detail->role->id)
                        ->icon('heroicon-o-check')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Non Aktifkan Akun')
                        ->modalDescription('Apakah anda ingin menon-aktifkan Akun ?')
                        ->modalSubmitActionLabel('Non Aktifkan')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Tables\Actions\EditAction::make()->visible(auth()->user()->role_detail->role->id === 1),
                ])->button()->label('Aksi')->color('info')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
