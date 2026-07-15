<?php

namespace App\Actions;

use App\Models\Booking;
use App\Services\PaymentService;

class ProcessPaymentAction
{
    public function __construct(private PaymentService $paymentService) {}

    public function execute(Booking $booking): array
    {
        $payment = $this->paymentService->processMockPayment($booking);

        return [
            'success' => $payment->status === 'paid',
            'payment' => $payment,
        ];
    }
}
