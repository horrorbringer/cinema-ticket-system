<?php

namespace Tests\Feature;

use App\Actions\ConfirmBookingAction;
use App\Actions\GenerateTicketAction;
use App\Actions\SendNotificationAction;
use App\Models\Booking;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\SeatLockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Showtime $showtime;
    private Seat $seat;
    private Seat $seat2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $seatType = SeatType::factory()->create(['price_multiplier' => 1.0]);

        $hall = Hall::factory()->create();
        $this->seat = Seat::factory()->create([
            'hall_id' => $hall->id,
            'seat_type_id' => $seatType->id,
            'label' => 'A1',
        ]);
        $this->seat2 = Seat::factory()->create([
            'hall_id' => $hall->id,
            'seat_type_id' => $seatType->id,
            'label' => 'A2',
        ]);

        $movie = Movie::factory()->create();
        $this->showtime = Showtime::factory()->create([
            'movie_id' => $movie->id,
            'hall_id' => $hall->id,
            'base_price' => 10.00,
            'start_time' => now()->addDay()->setTime(14, 0),
            'end_time' => now()->addDay()->setTime(16, 0),
        ]);
    }

    public function test_checkout_page_shows_selected_seats(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/checkout/' . $this->showtime->id . '?seats=' . $this->seat->id);

        $response->assertStatus(200);
        $response->assertSee('A1');
    }

    public function test_checkout_redirects_if_no_seats(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/checkout/' . $this->showtime->id);

        $response->assertRedirect('/showtimes/' . $this->showtime->id . '/seats');
    }

    public function test_store_booking_fails_when_seats_not_locked(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/bookings/' . $this->showtime->id, [
            'seats' => [$this->seat->id],
        ]);

        $response->assertSessionHas('error');
    }

    public function test_store_booking_succeeds_with_locked_seats(): void
    {
        $this->actingAs($this->user);

        $seatLockMock = Mockery::mock(SeatLockService::class);
        $seatLockMock->shouldReceive('isLocked')->andReturn(true);
        $seatLockMock->shouldReceive('getLockHolder')->andReturn($this->user->id);

        $this->app->instance(SeatLockService::class, $seatLockMock);

        $response = $this->post('/bookings/' . $this->showtime->id, [
            'seats' => [$this->seat->id, $this->seat2->id],
        ]);

        $response->assertRedirect();
    }

    public function test_payment_page_shows_booking_info(): void
    {
        $this->actingAs($this->user);

        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000001',
            'total_seats' => 1,
            'total_amount' => 10.00,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);
        $booking->items()->create([
            'seat_id' => $this->seat->id,
            'price' => 10.00,
        ]);

        $response = $this->get('/bookings/' . $booking->id . '/payment');

        $response->assertStatus(200);
        $response->assertSee('CIN-20260715-000001');
    }

    public function test_process_payment_success(): void
    {
        Notification::fake();

        $this->actingAs($this->user);

        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000002',
            'total_seats' => 1,
            'total_amount' => 10.00,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);
        $booking->items()->create([
            'seat_id' => $this->seat->id,
            'price' => 10.00,
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('processMockPayment')
            ->andReturn(Payment::make([
                'booking_id' => $booking->id,
                'payment_method' => 'mock',
                'transaction_id' => 'MOCK-TEST',
                'amount' => 10.00,
                'status' => 'paid',
            ]));
        $this->app->instance(PaymentService::class, $paymentServiceMock);

        $response = $this->post('/bookings/' . $booking->id . '/pay');

        $response->assertRedirect('/bookings/' . $booking->id . '/confirmation');
    }

    public function test_booking_history_shows_user_bookings(): void
    {
        $this->actingAs($this->user);

        Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000003',
            'total_seats' => 2,
            'total_amount' => 20.00,
            'status' => 'confirmed',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->get('/bookings');

        $response->assertStatus(200);
        $response->assertSee('CIN-20260715-000003');
    }

    public function test_guest_cannot_access_bookings(): void
    {
        $response = $this->get('/bookings');
        $response->assertRedirect('/login');

        $response = $this->get('/checkout/' . $this->showtime->id);
        $response->assertRedirect('/login');
    }

    public function test_ticket_download_requires_auth(): void
    {
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000004',
            'total_seats' => 1,
            'total_amount' => 10.00,
            'status' => 'confirmed',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->get('/bookings/' . $booking->id . '/tickets');
        $response->assertRedirect('/login');
    }

    public function test_booking_show_page_displays_details(): void
    {
        $this->actingAs($this->user);

        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000005',
            'total_seats' => 1,
            'total_amount' => 10.00,
            'status' => 'confirmed',
            'expires_at' => now()->addMinutes(10),
        ]);
        $booking->items()->create([
            'seat_id' => $this->seat->id,
            'price' => 10.00,
        ]);

        $response = $this->get('/bookings/' . $booking->id);
        $response->assertStatus(200);
        $response->assertSee('CIN-20260715-000005');
        $response->assertSee('Download Tickets');
    }

    public function test_cancel_pending_booking_succeeds(): void
    {
        Notification::fake();
        $this->actingAs($this->user);

        $seatLockMock = Mockery::mock(SeatLockService::class);
        $seatLockMock->shouldReceive('isLocked')->andReturn(true);
        $seatLockMock->shouldReceive('getLockHolder')->andReturn($this->user->id);

        $this->app->instance(SeatLockService::class, $seatLockMock);

        $response = $this->post('/bookings/' . $this->showtime->id, [
            'seats' => [$this->seat->id],
        ]);

        $booking = Booking::where('user_id', $this->user->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('pending', $booking->status);

        $seatLockMock->shouldReceive('release');

        $response = $this->post('/bookings/' . $booking->id . '/cancel');
        $response->assertRedirect('/bookings/' . $booking->id);
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    public function test_other_user_cannot_view_booking(): void
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $booking = Booking::create([
            'user_id' => $this->user->id,
            'showtime_id' => $this->showtime->id,
            'booking_number' => 'CIN-20260715-000006',
            'total_seats' => 1,
            'total_amount' => 10.00,
            'status' => 'confirmed',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->get('/bookings/' . $booking->id);
        $response->assertStatus(403);
    }
}
