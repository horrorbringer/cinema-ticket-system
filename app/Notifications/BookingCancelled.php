<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelled extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking, public bool $refunded = false) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Booking Cancelled - ' . $this->booking->booking_number)
            ->line('Your booking has been cancelled.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Movie: ' . $this->booking->showtime->movie->title);

        if ($this->refunded) {
            $message->line('A refund of $' . number_format($this->booking->total_amount, 2) . ' has been processed.');
        }

        return $message
            ->line('If you have any questions, please contact support.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'message' => 'Booking ' . $this->booking->booking_number . ' has been cancelled.',
            'refunded' => $this->refunded,
        ];
    }
}
