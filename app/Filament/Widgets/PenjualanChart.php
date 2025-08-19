<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan';
    protected static bool $isLazy = true;


    public function getDescription(): ?string
    {
        return 'The number of blog posts published per month.';
    }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
