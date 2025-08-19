<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\MUserRoleTabs;
use App\Models\Role;
use Filament\Actions\StaticAction;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = MUserRoleTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Role';
    protected static ?string $breadcrumb = "Role";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->role_detail->m_user_role_tabs_id == 1) return true;
        else return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('title')->label('Nama Role')->placeholder('Masukan Nama Role')->required(),
            // Select::make('m_status_tabs_id')
            //     ->label('Pilih Role')
            //     ->placeholder('Cari Role')
            //     ->options([
            //         2 => 'Admin',
            //         1 => 'Pria',
            //     ])
            //     ->native(false)
            //     ->default(1)
            //     ->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                MUserRoleTabs::whereNot('id',1)
            )
            ->columns([
            TextColumn::make('title')->label('Nama Role'),
            TextColumn::make('m_status_tabs_id')->badge()->label('Status')->alignment(Alignment::Center)
                ->getStateUsing(fn($record) => $record->status ? $record->status->title : '-')
                ->color(fn(string $state): string => match ($state) {
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
                        ->visible(fn($record) => $record->m_status_tabs_id === 2)
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Role')
                        ->modalDescription('Apakah anda ingin mengaktifkan Role ?')
                        ->modalSubmitActionLabel('Aktifkan')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Action::make('Notactive')
                        ->label('Non Aktifkan')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 2,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 3)
                        ->icon('heroicon-o-check')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Non Aktifkan Role')
                        ->modalDescription('Apakah anda ingin menon-aktifkan Role ?')
                        ->modalSubmitActionLabel('Non Aktifkan')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
