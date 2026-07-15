<?php

namespace App\Actions;

use App\Models\Booking;
use App\Services\PaymentService;
use App\Services\SeatLockService;

class ConfirmBookingAction
{
    public function __construct(
        private PaymentService $paymentService,
        private SeatLockService $seatLockService,
    ) {}

    public function execute(Booking $booking): array
    {
        if ($booking->status !== 'pending') {
            return ['success' => false, 'message' => 'Booking is not in pending status.'];
        }

        $payment = $this->paymentService->processMockPayment($booking);

        if ($payment->status === 'paid') {
            $booking->load('items.seat');
            foreach ($booking->items as $item) {
                $this->seatLockService->release($booking->showtime, $item->seat);
            }
            return ['success' => true, 'booking' => $booking, 'payment' => $payment];
        }

        $booking->update(['status' => 'cancelled']);
        return ['success' => false, 'message' => 'Payment failed.'];
    }
}
