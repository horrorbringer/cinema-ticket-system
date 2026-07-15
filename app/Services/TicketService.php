<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function generateTickets(Booking $booking): void
    {
        $booking->load('items.seat', 'showtime.movie', 'showtime.hall');

        foreach ($booking->items as $item) {
            $qrData = json_encode([
                'booking' => $booking->booking_number,
                'seat' => $item->seat->label,
                'movie' => $booking->showtime->movie->title,
                'time' => $booking->showtime->start_time->toDateTimeString(),
            ]);

            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($qrData));

            Ticket::create([
                'booking_id' => $booking->id,
                'seat_id' => $item->seat_id,
                'qr_code' => $qrCode,
                'ticket_number' => $booking->booking_number . '-' . $item->seat->label,
                'status' => 'active',
            ]);
        }
    }

    public function downloadPdf(Booking $booking)
    {
        $booking->load('tickets', 'items.seat', 'showtime.movie', 'showtime.hall', 'user');

        $pdf = Pdf::loadView('tickets.pdf', compact('booking'));

        return $pdf->download("ticket-{$booking->booking_number}.pdf");
    }
}
