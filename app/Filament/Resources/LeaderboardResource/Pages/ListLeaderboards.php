<?php

namespace App\Filament\Resources\LeaderboardResource\Pages;

use App\Filament\Resources\LeaderboardResource;
use App\Filament\Widgets\PenjualanChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaderboards extends ListRecords
{
    protected static string $resource = LeaderboardResource::class;
    protected static ?string $title = 'Leaderboard';
    protected ?string $heading = 'Data Leaderboard';
    protected function getHeaderWidgets(): array
    {
        return [
            PenjualanChart::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
