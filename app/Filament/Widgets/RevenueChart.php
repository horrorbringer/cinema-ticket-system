<?php

namespace App\Filament\Widgets;

use App\Services\ReportService;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Daily Revenue (Last 30 Days)';

    protected function getData(): array
    {
        $report = app(ReportService::class);
        $trends = $report->bookingTrends(30);

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => array_column($trends, 'revenue'),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($trends, 'date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
