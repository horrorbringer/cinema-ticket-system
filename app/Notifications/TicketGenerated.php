<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketGenerated extends Notification
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
            ->subject('Your Tickets - ' . $this->booking->booking_number)
            ->line('Your tickets are ready.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Movie: ' . $this->booking->showtime->movie->title)
            ->action('Download Tickets', url('/bookings/' . $this->booking->id . '/tickets'))
            ->attachData(
                \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf', ['booking' => $this->booking])->output(),
                'tickets-' . $this->booking->booking_number . '.pdf'
            );
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Tickets generated for booking ' . $this->booking->booking_number,
        ];
    }
}
