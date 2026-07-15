<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    public function processMockPayment(Booking $booking): Payment
    {
        $success = rand(1, 10) > 1;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'mock',
            'transaction_id' => 'MOCK-' . Str::random(16),
            'amount' => $booking->total_amount,
            'status' => $success ? 'paid' : 'failed',
            'payload' => ['simulated' => true, 'success' => $success],
        ]);

        if ($success) {
            $booking->update(['status' => 'confirmed']);
        }

        return $payment;
    }
}
