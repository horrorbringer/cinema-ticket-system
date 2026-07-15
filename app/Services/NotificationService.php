<?php

namespace App\Services;

use App\Models\Booking;
use App\Notifications\BookingCancelled;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentReceived;
use App\Notifications\TicketGenerated;

class NotificationService
{
    public function sendBookingConfirmation(Booking $booking): void
    {
        $booking->user->notify(new BookingConfirmed($booking));
    }

    public function sendPaymentReceipt(Booking $booking): void
    {
        $booking->user->notify(new PaymentReceived($booking));
    }

    public function sendTicketNotification(Booking $booking): void
    {
        $booking->user->notify(new TicketGenerated($booking));
    }

    public function sendCancellationNotification(Booking $booking, bool $refunded = false): void
    {
        $booking->user->notify(new BookingCancelled($booking, $refunded));
    }
}
