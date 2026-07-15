<?php

namespace App\Http\Controllers;

use App\Actions\CancelBookingAction;
use App\Actions\ConfirmBookingAction;
use App\Actions\CreateBookingAction;
use App\Actions\SendNotificationAction;
use App\Jobs\GenerateTicketsJob;
use App\Models\Booking;
use App\Models\Showtime;
use App\Services\SeatLockService;
use App\Services\TicketService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function checkout(Showtime $showtime, Request $request)
    {
        $showtime->load('movie', 'hall');

        $seatIds = $request->input('seats', []);
        if (is_string($seatIds)) {
            $seatIds = explode(',', $seatIds);
        }
        if (empty($seatIds)) {
            return redirect()->route('showtimes.seats', $showtime)
                ->with('error', 'Please select at least one seat.');
        }

        $seats = $showtime->hall->seats()->with('seatType')->whereIn('id', $seatIds)->get();

        $totalAmount = 0;
        foreach ($seats as $seat) {
            $totalAmount += $showtime->base_price * $seat->seatType->price_multiplier;
        }

        return view('bookings.checkout', compact('showtime', 'seats', 'totalAmount'));
    }

    public function store(Showtime $showtime, Request $request, CreateBookingAction $createBooking, SeatLockService $seatLockService)
    {
        $seatIds = $request->input('seats', []);
        if (empty($seatIds)) {
            return back()->with('error', 'No seats selected.');
        }

        $result = $createBooking->execute($request->user(), $showtime, $seatIds);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('bookings.payment', $result['booking'])
            ->with('success', 'Seats locked! Proceed to payment.');
    }

    public function payment(Booking $booking)
    {
        $booking->load('items.seat', 'showtime.movie', 'showtime.hall');
        return view('bookings.payment', compact('booking'));
    }

    public function processPayment(Booking $booking, Request $request, ConfirmBookingAction $confirmBooking, SendNotificationAction $sendNotification)
    {
        $result = $confirmBooking->execute($booking);

        if (!$result['success']) {
            return redirect()->route('showtimes.seats', $booking->showtime)
                ->with('error', $result['message']);
        }

        GenerateTicketsJob::dispatch($booking);

        $sendNotification->execute($booking, 'booking_confirmed');
        $sendNotification->execute($booking, 'payment_receipt');
        $sendNotification->execute($booking, 'ticket_generated');

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Payment successful!');
    }

    public function confirmation(Booking $booking)
    {
        $booking->load('items.seat', 'showtime.movie', 'showtime.hall');
        return view('bookings.confirmation', compact('booking'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $booking->load('items.seat.seatType', 'showtime.movie', 'showtime.hall', 'payment', 'tickets');

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking, CancelBookingAction $cancelBooking, SendNotificationAction $sendNotification)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $result = $cancelBooking->execute($booking);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        $notifType = $result['refunded'] ? 'booking_refunded' : 'booking_cancelled';
        $sendNotification->execute($booking, $notifType);

        return redirect()->route('bookings.show', $booking)
            ->with('success', $result['message']);
    }

    public function history()
    {
        $bookings = auth()->user()->bookings()
            ->with('showtime.movie', 'items.seat')
            ->latest()
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function downloadTickets(Booking $booking, TicketService $ticketService)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return $ticketService->downloadPdf($booking);
    }
}
