<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function handle(): void
    {
        $this->booking->load('items.seat', 'showtime.movie', 'showtime.hall');

        foreach ($this->booking->items as $item) {
            $qrData = json_encode([
                'booking' => $this->booking->booking_number,
                'seat' => $item->seat->label,
                'movie' => $this->booking->showtime->movie->title,
                'time' => $this->booking->showtime->start_time->toDateTimeString(),
            ]);

            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($qrData));

            Ticket::create([
                'booking_id' => $this->booking->id,
                'seat_id' => $item->seat_id,
                'qr_code' => $qrCode,
                'ticket_number' => $this->booking->booking_number . '-' . $item->seat->label,
                'status' => 'active',
            ]);
        }
    }
}
