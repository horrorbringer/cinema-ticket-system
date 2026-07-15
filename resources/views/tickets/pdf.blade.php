<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $booking->booking_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .ticket { border: 2px solid #333; padding: 20px; margin: 20px; page-break-after: always; }
        .header { text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 15px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        .details { margin-bottom: 20px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 8px; }
        .details td:first-child { font-weight: bold; width: 40%; }
        .qr { text-align: center; margin: 20px 0; }
        .qr img { width: 150px; height: 150px; }
        .footer { text-align: center; font-size: 12px; color: #999; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    @foreach($booking->tickets as $ticket)
        <div class="ticket">
            <div class="header">
                <h1>CINEMA TICKET</h1>
                <p>{{ $booking->showtime->movie->title }}</p>
            </div>
            <div class="details">
                <table>
                    <tr><td>Booking #</td><td>{{ $booking->booking_number }}</td></tr>
                    <tr><td>Ticket #</td><td>{{ $ticket->ticket_number }}</td></tr>
                    <tr><td>Seat</td><td>{{ $ticket->seat->label }}</td></tr>
                    <tr><td>Hall</td><td>{{ $booking->showtime->hall->name }}</td></tr>
                    <tr><td>Date</td><td>{{ $booking->showtime->start_time->format('l, F j, Y') }}</td></tr>
                    <tr><td>Time</td><td>{{ $booking->showtime->start_time->format('h:i A') }}</td></tr>
                    <tr><td>Amount</td><td>${{ number_format($ticket->booking->total_amount, 2) }}</td></tr>
                </table>
            </div>
            @if($ticket->qr_code)
                <div class="qr">
                    <img src="data:image/png;base64,{{ $ticket->qr_code }}" alt="QR Code">
                </div>
            @endif
            <div class="footer">
                <p>Thank you for choosing Cinema Ticketing System</p>
            </div>
        </div>
    @endforeach
</body>
</html>
