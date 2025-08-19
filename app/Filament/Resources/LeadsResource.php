<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadsResource\Pages;
use App\Filament\Resources\LeadsResource\RelationManagers;
use App\Models\Leads;
use App\Models\MProjectTab;
use App\Models\MStatusTabs;
use App\Models\MUnitTab;
use App\Models\TFinanceTab;
use App\Models\TLeadDetailTabs;
use App\Models\TLeadTabs;
use App\Models\TMarketingTab;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadsResource extends Resource
{
    protected static ?string $model = TLeadTabs::class;
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Leads';
    protected static ?string $breadcrumb = "Leads";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Detail Booking')
                ->schema([
                    TextEntry::make('created_by')->label('Dibuat Oleh')
                        ->getStateUsing(fn($record) => $record->createdby?->name ?? '-'),
                    TextEntry::make('t_marketing_tabs_id')->label('Marketing Closing')
                        ->getStateUsing(fn($record) => $record->marketing?->name ?? '-'),
                    TextEntry::make('m_unit_tabs_id')
                        ->label('Unit')
                        ->getStateUsing(fn($record) => $record->unit?->title ?? '-'),
                    TextEntry::make('booking_date')
                        ->label('Booking Date')
                        ->date(),
                    TextEntry::make('booking_date')
                        ->label('Biaya Booking')->getStateUsing(fn($record) => 'Rp. ' . $record->paid),
                ])->columns(2),
            Section::make('Detail Leads')
                ->schema([
                    RepeatableEntry::make('lead.detail')
                        ->schema([
                            TextEntry::make('status.title')->badge(),
                            TextEntry::make('marketing.name')->label('Marketing'),
                            TextEntry::make('visit_date')->date()->label('Tanggal Visit'),
                            TextEntry::make('description'),
                        ])
                        ->columns(3)
                ]),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('customer_nama')->label('Nama Customer')->placeholder('Masukan Nama Customer'),
            TextInput::make('customer_phone')->label('No Telp/Wa')->numeric()->placeholder('Masukan No Telp/Wa')->required(),
            Textarea::make('customer_address')->label('Alamat')->placeholder('Masukan Alamat')->required(),
            DatePicker::make('lead_in')->label('Waktu Chat Masuk')->required(),
            Select::make('m_project_tabs_id')
                ->label('Pilih Project')
                ->relationship('project', 'm_project_tabs_id')
                ->placeholder('Cari Project')
                ->options(MProjectTab::where('m_company_tabs_id', auth()->user()->id)->pluck('title', 'id'))
                ->getSearchResultsUsing(fn(string $search): array => MProjectTab::where('m_company_tabs_id', auth()->user()->id)
                    ->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                ->getOptionLabelUsing(fn($value): ?string => MProjectTab::find($value)?->title)
                ->required(),
            Select::make('m_status_tabs_id')
                ->label('Pilih Status')
                ->relationship('status', 'm_status_tabs_id')
                ->placeholder('Cari Status')
                ->options(MStatusTabs::whereIn('id', [4,5,6])->pluck('title', 'id'))
                ->getSearchResultsUsing(fn(string $search): array => MStatusTabs::whereIn('id', [4,5,6])
                    ->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                ->getOptionLabelUsing(fn($value): ?string => MStatusTabs::find($value)?->title)
                ->required(),
            Textarea::make('description')->label('Catatan')->placeholder('Masukan Catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                TLeadTabs::orderBy('id','desc')
            )
            ->emptyStateHeading('Leads Kosong')
            ->emptyStateDescription('Tidak ada informasi Leads')
            ->columns([
                TextColumn::make('customer_nama')->label('Nama'),
                TextColumn::make('customer_phone')->label('No Telp/Wa'),
                TextColumn::make('customer_address')->label('Alamat'),
                TextColumn::make('created_by')->label('Dibuat')->alignment(Alignment::Center)->badge()->getStateUsing(fn($record) => $record->user?->name ?? '-'),
                TextColumn::make('lead_in')->label('Awal Chat')->date()->alignment(Alignment::Center),
                TextColumn::make('m_status_tabs_id')->badge()->label('Status')->alignment(Alignment::Center)
                    ->getStateUsing(fn($record) => $record->status ? $record->status->title : '-')
                    ->color(fn(string $state): string => match ($state) {
                        'TANYA-TANYA' => 'gray',
                        'HOT' => 'success',
                        'HANYA CHAT' => 'danger',
                    }),
                TextColumn::make('last_status')->badge()->label('Visit/Booking')->alignment(Alignment::Center)
                    ->getStateUsing(fn($record) => $record?->last_status ?? null)
                    ->color(fn(string $state): string => match ($state) {
                        'VISIT' => 'info',
                        'BOOKING' => 'success',
                        'CANCEL VISIT' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('aktivity')
                        ->label('Visit')
                        ->action(function (array $data,$record) {
                            TLeadDetailTabs::create([
                                'created_by' => auth()->user()->id,
                                't_lead_tabs_id' => $record->id,
                                't_marketing_tabs_id' => $data['t_marketing_tabs_id'],
                                'm_status_tabs_id' => $data['m_status_tabs_id'],
                                'visit_date' => $data['visit_date'],
                                'description' => $data['description'],
                            ]);
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->body('Data visit berhasil disimpan')
                                ->send();
                        })
                        ->visible(fn($record) => TLeadTabs::where('id', $record->id)->doesnthave('booking')->first())
                        ->form([
                            Grid::make()->schema([
                                Select::make('t_marketing_tabs_id')
                                    ->label('Pilih Marketing')
                                    ->placeholder('Cari Marketing')
                                    ->options(TMarketingTab::whereHas('atasan', function ($a) {
                                        $a->where('m_company_tabs_id', auth()->user()->id);
                                    })->where('m_status_tabs_id', 3)->pluck('name', 'id'))
                                    ->getSearchResultsUsing(fn(string $search): array => TMarketingTab::whereHas('atasan', function ($a) {
                                        $a->where('m_company_tabs_id', auth()->user()->id);
                                    })->where('m_status_tabs_id', 3)
                                        ->where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => TMarketingTab::find($value)?->name),
                                Select::make('m_status_tabs_id')
                                    ->label('Pilih Status')
                                    ->relationship('status', 'm_status_tabs_id')
                                    ->placeholder('Cari Status')
                                    ->options(MStatusTabs::whereIn('id', [8,9])->pluck('title', 'id'))
                                    ->getSearchResultsUsing(fn(string $search): array => MStatusTabs::whereIn('id', [8,9])
                                        ->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => MStatusTabs::find($value)?->title)
                                    ->required(),
                                DatePicker::make('visit_date')->label('Tgl Visit')->required(),
                                Textarea::make('description')->label('Catatan')->placeholder('Masukan Catatan'),
                            ])->columns(2)
                        ])
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->modalHeading('Tambah Informasi Visit')
                        ->modalSubmitActionLabel('Simpan Data')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Action::make('booking')
                        ->label('Booking')
                        ->action(function (array $data, $record) {
                            TFinanceTab::create([
                                'created_by' => auth()->user()->id,
                                't_lead_tabs_id' => $record->id,
                                't_marketing_tabs_id' => $data['t_marketing_tabs_id'],
                                'm_unit_tabs_id' => $data['m_unit_tabs_id'],
                                'm_status_tabs_id' => 7,
                                'paid' => $data['paid'],
                                'booking_date' => $data['booking_date'],
                                'description' => $data['description'],
                            ]);
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->body('Data Booking berhasil disimpan')
                                ->send();
                        })
                        ->form([
                            Grid::make()->schema([
                                Select::make('t_marketing_tabs_id')
                                    ->label('Pilih Marketing')
                                    ->placeholder('Cari Marketing')
                                    ->options(TMarketingTab::whereHas('atasan', function ($a) {
                                        $a->where('m_company_tabs_id', auth()->user()->id);
                                    })->where('m_status_tabs_id', 3)->pluck('name', 'id'))
                                    ->getSearchResultsUsing(fn(string $search): array => TMarketingTab::whereHas('atasan', function ($a) {
                                        $a->where('m_company_tabs_id', auth()->user()->id);
                                    })->where('m_status_tabs_id', 3)
                                        ->where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                    ->required()
                                    ->getOptionLabelUsing(fn($value): ?string => TMarketingTab::find($value)?->name),
                                Select::make('m_unit_tabs_id')
                                    ->label('Pilih Unit')
                                    ->placeholder('Cari Unit')
                                    ->options(MUnitTab::where('m_company_tabs_id',auth()->user()->m_company_tabs_id)->pluck('title', 'id'))
                                    ->getSearchResultsUsing(fn(string $search): array => MUnitTab::where('m_company_tabs_id', auth()->user()->m_company_tabs_id)
                                        ->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => MUnitTab::find($value)?->title)
                                    ->required(),
                                TextInput::make('paid')->label('Biaya Booking')->required(),
                                DatePicker::make('booking_date')->label('Tgl Booking')->required(),
                                Textarea::make('description')->label('Catatan')->placeholder('Masukan Catatan'),
                            ])->columns(2)
                        ])
                        ->visible(fn($record) => TLeadTabs::where('id', $record->id)->doesnthave('booking')->first())
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->modalHeading('Tambah Informasi Booking')
                        ->modalSubmitActionLabel('Simpan Data')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),

                Tables\Actions\ViewAction::make()->label('Detail')->modalHeading('Detail Perjualan'),
                    Tables\Actions\EditAction::make()->visible(
                    fn($record) =>
                        (auth()->user()->role_detail->role->id === 1 || auth()->user()->role_detail->role->id === 2) && TLeadTabs::where('id', $record->id)->doesnthave('booking')->first()
                    ),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLeads::route('/create'),
            'edit' => Pages\EditLeads::route('/{record}/edit'),
        ];
    }
}
