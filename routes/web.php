<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SeatLockController;
use App\Http\Controllers\ShowtimeController;
use App\Models\Movie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'movies' => Movie::whereIn('status', ['now_showing', 'coming_soon'])->latest()->get(),
    ]);
})->name('home');

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movies/{movie}/showtimes/{date}', [MovieController::class, 'showtimesDate'])->name('movies.showtimes-date');
Route::get('/movies/{movie}/related', [MovieController::class, 'related'])->name('movies.related');
Route::get('/showtimes/{showtime}/seats', [ShowtimeController::class, 'seats'])->name('showtimes.seats');
Route::get('/showtimes/{showtime}/seat-status', [SeatLockController::class, 'status'])->name('showtimes.seat-status');

Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::middleware('auth')->group(function () {
    Route::post('/seats/{showtime}/lock/{seat}', [SeatLockController::class, 'lock'])->name('seats.lock');
    Route::post('/seats/{showtime}/release/{seat}', [SeatLockController::class, 'release'])->name('seats.release');

    Route::get('/checkout/{showtime}', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::post('/bookings/{showtime}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.process-payment');
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/tickets', [BookingController::class, 'downloadTickets'])->name('bookings.tickets');
    Route::get('/bookings', [BookingController::class, 'history'])->name('bookings.history');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
