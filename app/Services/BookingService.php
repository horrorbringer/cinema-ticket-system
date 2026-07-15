<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Support\Str;

class BookingService
{
    public function generateBookingNumber(): string
    {
        $date = now()->format('Ymd');
        $last = Booking::whereDate('created_at', today())->count() + 1;

        return 'CIN-' . $date . '-' . str_pad($last, 6, '0', STR_PAD_LEFT);
    }

    public function createPendingBooking(User $user, Showtime $showtime, array $seatData): Booking
    {
        $totalSeats = count($seatData);
        $totalAmount = array_sum(array_column($seatData, 'price'));

        $booking = Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'booking_number' => $this->generateBookingNumber(),
            'total_seats' => $totalSeats,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        foreach ($seatData as $seat) {
            $booking->items()->create([
                'seat_id' => $seat['seat_id'],
                'price' => $seat['price'],
            ]);
        }

        return $booking->load('items', 'showtime.movie', 'showtime.hall');
    }
}
