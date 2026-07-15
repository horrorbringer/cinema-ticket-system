<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public function query()
    {
        $query = Payment::with('booking.showtime.movie')
            ->where('status', 'paid');

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Booking #',
            'Movie',
            'Amount',
            'Status',
            'Paid At',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->transaction_id,
            $payment->booking?->booking_number ?? 'N/A',
            $payment->booking?->showtime?->movie?->title ?? 'N/A',
            number_format($payment->amount, 2),
            $payment->status,
            $payment->created_at->format('Y-m-d H:i'),
        ];
    }
}
