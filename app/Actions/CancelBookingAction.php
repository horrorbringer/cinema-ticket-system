<?php

namespace App\Actions;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\SeatLockService;
use Illuminate\Support\Str;

class CancelBookingAction
{
    public function __construct(private SeatLockService $seatLockService) {}

    public function execute(Booking $booking, string $reason = 'customer_request'): array
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return ['success' => false, 'message' => 'Booking cannot be cancelled.'];
        }

        if ($booking->showtime->start_time->isPast()) {
            return ['success' => false, 'message' => 'Cannot cancel a past showtime.'];
        }

        $booking->load('items.seat', 'payment');

        foreach ($booking->items as $item) {
            $this->seatLockService->release($booking->showtime, $item->seat);
        }

        if ($booking->payment && $booking->payment->status === 'paid') {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'mock_refund',
                'transaction_id' => 'REFUND-' . Str::random(16),
                'amount' => -$booking->total_amount,
                'status' => 'refunded',
                'payload' => ['refunded' => true, 'reason' => $reason],
            ]);

            $booking->tickets()->update(['status' => 'cancelled']);
            $booking->update(['status' => 'refunded']);

            return [
                'success' => true,
                'message' => 'Booking cancelled and refunded.',
                'refunded' => true,
            ];
        }

        $booking->tickets()->update(['status' => 'cancelled']);
        $booking->update(['status' => 'cancelled']);

        return [
            'success' => true,
            'message' => 'Booking cancelled.',
            'refunded' => false,
        ];
    }
}
