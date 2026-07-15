# Implementation Plan — Cinema Ticketing System

## Phase 1: Foundation
1. Install required packages (Filament, Spatie Permission, Reverb, QR Code, Excel, DomPDF, Activity Log)
2. Set up auth (Laravel Breeze Blade stack)
3. Set up Filament admin panel
4. Configure Redis + Reverb
5. Set up Spatie roles/permissions
6. Run base migration + seed admin user

## Phase 2: Core Modules — Admin (Filament Resources)
7. Genre Resource
8. Movie Resource
9. Hall Resource
10. Seat Type Resource
11. Seat Resource
12. Showtime Resource (with overlap validation)

## Phase 3: Customer Website
13. Customer auth (login/register/forgot password/email verification)
14. Movie listing page (with HTMX search/filter)
15. Movie detail page + showtime selection
16. Seat map with HTMX dynamic loading
17. Real-time seat locking (Reverb + Redis)

## Phase 4: Booking & Payment
18. Service layer (MovieService, ShowtimeService, SeatLockService, BookingService, PaymentService, TicketService, NotificationService)
19. Action classes (LockSeatAction, UnlockSeatAction, CreateBookingAction, ConfirmBookingAction, GenerateTicketAction, ProcessPaymentAction, SendNotificationAction)
20. Checkout flow
21. Mock payment gateway
22. Seat lock TTL expiry handling

## Phase 5: Tickets & Notifications
23. QR code + PDF ticket generation (queued)
24. Email notifications
25. Booking history page
26. Profile management

## Phase 6: Admin Dashboard & Analytics
27. Filament dashboard (revenue, booking, customer cards)
28. Charts (daily/monthly revenue, booking trends)
29. Report exports (Laravel Excel)
