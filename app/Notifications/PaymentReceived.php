<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - ' . $this->booking->booking_number)
            ->line('Your payment of $' . number_format($this->booking->total_amount, 2) . ' has been received.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->action('View Booking', url('/bookings/' . $this->booking->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'amount' => $this->booking->total_amount,
            'message' => 'Payment received for booking ' . $this->booking->booking_number,
        ];
    }
}
