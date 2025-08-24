<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingResource\Pages;
use App\Models\TMarketingTab;
use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketingResource extends Resource
{
    protected static ?string $model = TMarketingTab::class;
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Anggota';
    protected static ?string $breadcrumb = "Anggota";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (
            auth()->user()->role_detail->m_user_role_tabs_id == 1
            || auth()->user()->role_detail->m_user_role_tabs_id == 3
        ) return true;
        else return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')->label('Nama')->placeholder('Masukan Nama Marketing')->required(),
            TextInput::make('phone')->label('No. WhatsApps')->numeric()->placeholder('Masukan No. WhatsApps Marketing')->required(),
            Select::make('atasan_id')
                ->label('Pilih SPV')
                ->relationship('atasan', 'atasan_id')
                ->placeholder('Cari SPV')
                ->getSearchResultsUsing(
                    function (string $search) {
                        return User::with('role_detail')
                            ->where('m_status_tabs_id',3)
                            ->where('name', 'like', '%' . $search . '%')
                            ->whereHas('role_detail', function ($a) {
                                $a->where('m_user_role_tabs_id', 3);
                            })
                            ->limit(5)
                            ->get()
                            ->pluck('name', 'id');
                    }
                )
                ->searchable()
                ->required(),
            FileUpload::make('photo')->label('Upload Foto')
                ->uploadingMessage('Uploading attachment...')
                ->reorderable()
                ->preserveFilenames()
                ->image()
                ->directory('photo')
                ->maxSize(5000)->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Marketing Kosong')
            ->emptyStateDescription('Tidak ada informasi Marketing')
            ->columns([
                TextColumn::make('name')->label('Pengguna'),
                TextColumn::make('atasan_id')->label('SPV')
                    ->getStateUsing(fn($record) => $record->atasan?->name ?? '-'),
                ImageColumn::make('photo')->label('Foto')->alignment(Alignment::Center)->getStateUsing(function (TMarketingTab $record): string {
                    return $record->photo;
                })->circular(),
                TextColumn::make('m_status_tabs_id')->badge()->label('Status')->alignment(Alignment::Center)
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
            'index' => Pages\ListMarketings::route('/'),
            'create' => Pages\CreateMarketing::route('/create'),
            'edit' => Pages\EditMarketing::route('/{record}/edit'),
        ];
    }
}
