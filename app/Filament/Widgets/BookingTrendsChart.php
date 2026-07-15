<?php

namespace App\Filament\Widgets;

use App\Services\ReportService;
use Filament\Widgets\ChartWidget;

class BookingTrendsChart extends ChartWidget
{
    protected ?string $heading = 'Booking Trends (Last 30 Days)';

    protected function getData(): array
    {
        $report = app(ReportService::class);
        $trends = $report->bookingTrends(30);

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => array_column($trends, 'bookings'),
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#16a34a',
                ],
            ],
            'labels' => array_column($trends, 'date'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
