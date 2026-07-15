<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
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
            ->subject('Booking Confirmed - ' . $this->booking->booking_number)
            ->line('Your booking has been confirmed.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Movie: ' . $this->booking->showtime->movie->title)
            ->line('Date: ' . $this->booking->showtime->start_time->format('l, F j, Y \a\t h:i A'))
            ->action('View Booking', url('/bookings/' . $this->booking->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'message' => 'Booking ' . $this->booking->booking_number . ' confirmed.',
        ];
    }
}
