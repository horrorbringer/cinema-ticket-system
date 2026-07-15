<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private ?string $status = null,
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public function query()
    {
        $query = Booking::with('user', 'showtime.movie', 'payment');

        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Booking #',
            'Customer',
            'Movie',
            'Seats',
            'Total Amount',
            'Status',
            'Payment Status',
            'Booked At',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->booking_number,
            $booking->user?->name ?? 'N/A',
            $booking->showtime?->movie?->title ?? 'N/A',
            $booking->items->pluck('seat.label')->join(', '),
            number_format($booking->total_amount, 2),
            $booking->status,
            $booking->payment?->status ?? 'N/A',
            $booking->created_at->format('Y-m-d H:i'),
        ];
    }
}
