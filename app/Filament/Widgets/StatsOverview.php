<?php

namespace App\Filament\Widgets;

use App\Services\ReportService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $report = app(ReportService::class);
        $stats = $report->dashboardStats();

        return [
            Stat::make('Total Revenue', '$' . number_format($stats['total_revenue'], 2))
                ->description('All time revenue')
                ->chartColor('success'),
            Stat::make('Total Bookings', $stats['total_bookings'])
                ->description('All bookings made'),
            Stat::make('Total Customers', $stats['total_customers'])
                ->description('Registered users'),
            Stat::make('Total Movies', $stats['total_movies'])
                ->description('Movies in system'),
        ];
    }
}
