<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class ReportService
{
    public function dailyRevenue(?string $date = null): array
    {
        $date = $date ? Carbon::parse($date) : today();

        $revenue = Payment::whereDate('created_at', $date)
            ->where('status', 'paid')
            ->sum('amount');

        $bookings = Booking::whereDate('created_at', $date)->count();

        return ['date' => $date->format('Y-m-d'), 'revenue' => $revenue, 'bookings' => $bookings];
    }

    public function monthlyRevenue(?int $year = null, ?int $month = null): array
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        $revenue = Payment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'paid')
            ->sum('amount');

        $bookings = Booking::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        return ['year' => $year, 'month' => $month, 'revenue' => $revenue, 'bookings' => $bookings];
    }

    public function bookingTrends(int $days = 30): array
    {
        $trends = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'bookings' => Booking::whereDate('created_at', $date)->count(),
                'revenue' => Payment::whereDate('created_at', $date)->where('status', 'paid')->sum('amount'),
            ];
        }
        return $trends;
    }

    public function dashboardStats(): array
    {
        return [
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'total_bookings' => Booking::count(),
            'total_customers' => User::count(),
            'total_movies' => Movie::count(),
        ];
    }
}
