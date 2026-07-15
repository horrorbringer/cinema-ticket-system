<?php

namespace App\Actions;

use App\Models\Booking;
use App\Services\TicketService;

class GenerateTicketAction
{
    public function __construct(private TicketService $ticketService) {}

    public function execute(Booking $booking): void
    {
        $this->ticketService->generateTickets($booking);
    }
}
