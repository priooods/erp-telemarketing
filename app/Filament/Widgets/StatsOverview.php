<?php

namespace App\Filament\Widgets;

use App\Models\TLeadTabs;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $hot = TLeadTabs::where('m_status_tabs_id',4)->whereMonth('lead_in', Carbon::now()->format('m'))->count();
        $question = TLeadTabs::where('m_status_tabs_id',5)->whereMonth('lead_in', Carbon::now()->format('m'))->count();
        $chat = TLeadTabs::where('m_status_tabs_id', 6)->whereMonth('lead_in', Carbon::now()->format('m'))->count();
        return [
            Stat::make('HOT', $hot)->description(Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')->color('success'),
            Stat::make('TANYA-TANYA', $question)->description(Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')->color('info'),
            Stat::make('HANYA CHAT', $chat)->description(Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')->color('danger'),
        ];
    }
}
