<?php

namespace App\Actions;

use App\Models\Booking;
use App\Services\NotificationService;

class SendNotificationAction
{
    public function __construct(private NotificationService $notificationService) {}

    public function execute(Booking $booking, string $type): void
    {
        match ($type) {
            'booking_confirmed' => $this->notificationService->sendBookingConfirmation($booking),
            'payment_receipt' => $this->notificationService->sendPaymentReceipt($booking),
            'ticket_generated' => $this->notificationService->sendTicketNotification($booking),
            'booking_cancelled' => $this->notificationService->sendCancellationNotification($booking),
            'booking_refunded' => $this->notificationService->sendCancellationNotification($booking, true),
            default => null,
        };
    }
}
