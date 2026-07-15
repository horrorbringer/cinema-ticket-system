# SYSTEM ARCHITECTURE SPECIFICATION 

Online Ticketing & Seat Selection System for Cinema Enterprise 

# Technology Stack 

## Backend 

- Laravel 13 

- PHP 8.4+ 

- MySQL 8 

- Redis 

- Laravel Reverb 

- Laravel Queue 

## Admin System 

- Filament 4 

- Livewire 

- Alpine.js 

## Customer Website 

- Laravel Blade 

- HTMX 

- Alpine.js 

- Tailwind CSS 

## Supporting Packages 

- Simple QR Code 

- Laravel Excel 

- Spatie Permission 

- Laravel Activity Log 

- Laravel DomPDF 

# Architecture Philosophy 

The project follows a **Server-Driven UI Architecture** . 

The server remains responsible for: 

- Business logic 

- Validation 

- Authorization 

- HTML rendering 

- Seat availability management 

- Payment processing 

HTMX is used only to enhance user interaction without creating a Single Page Application (SPA). 

The project must NOT use: 

- React 

- Vue 

- Next.js 

- Nuxt.js 

- Inertia.js 

The objective is to maintain a simple, maintainable, and fast architecture. 

# Frontend Architecture 

## HTMX Responsibilities 

HTMX must be used for: 

Movie Search 

Dynamic search without page refresh. 

Example: 

- Search by movie title 

- Search by genre 

Movie Filtering 

Filter movies by: 

- Genre 

- Language 

- Status 

Without reloading page. 

### Showtime Filtering 

Filter showtimes by: 

- Date 

- Hall 

- Movie 

Without reloading page. 

### Dynamic Seat Map Loading 

Load seat layout dynamically. 

Example: 

Customer selects: 

Movie → Showtime 

System loads seat map via HTMX request. 

### Booking Summary Updates 

Update: 

- Selected seats 

- Quantity 

- Total amount 

Immediately after seat selection. 

### Booking History Pagination 

Load booking records dynamically. 

No full page reload. 

### Profile Updates 

Update customer profile information using HTMX forms. 

# Real-Time Architecture 

## Problem 

HTMX polling alone is insufficient. 

Example: 

User A selects A5. 

User B opens seat map. 

User B may still see A5 available. 

This creates race conditions. 

## Solution 

Use Laravel Reverb + Redis. 

Event Flow 

Customer selects seat. 

↓ 

SeatLockCreated Event 

↓ 

Broadcast via Reverb 

↓ 

All connected clients receive update 

↓ 

Seat instantly changes state 

# Seat Management System 

## Seat States 

AVAILABLE 

LOCKED 

RESERVED 

SOLD 

DISABLED 

Seat Lifecycle 

Available 

Customer can select. 

↓ 

Locked 

Seat temporarily reserved. Redis TTL = 300 seconds. 

↓ 

Reserved 

Payment process started. 

↓ 

Sold 

Payment successful. 

↓ 

Ticket generated. 

Expired Lock 

After 5 minutes: 

Redis automatically expires. 

Seat becomes AVAILABLE. 

# Redis Seat Lock Design 

Key Structure 

seat_lock:{showtime_id}:{seat_id} 

Example seat_lock:15:A5 

TTL 

300 seconds 

Stored Value 

{ "user_id": 10, "seat": "A5", "showtime_id": 15, "locked_at": "2026-07-15 14:00:00" } 

# Core Modules 

## Authentication Module 

Customer: 

- Register 

- Login 

- Forgot Password 

- Email Verification 

Admin: 

- Filament Authentication 

## Movie Module 

Fields: 

- Title 

- Description 

- Poster 

- Trailer 

- Duration 

- Genre 

- Language 

- Release Date 

- Rating 

- Status 

#### Status: 

- Coming Soon 

- Now Showing 

- Ended 

## Cinema Module 

### Cinema Hall 

Fields: 

- Name 

- Capacity 

- Status 

### Seat Types 

Types: 

- Standard 

- Premium 

- VIP 

Each type contains: 

- Name 

- Color 

 Price Multiplier 

## Schedule Module 

Fields: 

- Movie 

- Hall 

- Start Time 

- End Time 

- Base Price 

Validation: 

No overlapping showtimes in same hall. 

## Booking Module 

Booking Flow 

Select Movie 

↓ 

Select Showtime 

↓ 

Select Seats 

↓ 

Lock Seats 

↓ 

Checkout 

↓ 

Payment 

#### ↓ 

Booking Confirmation 

↓ 

#### Ticket Generation 

## Payment Module 

Version 1 

Mock Payment Gateway 

Version 2 

ABA PayWay Sandbox 

Payment Status 

- Pending 

- Paid 

- Failed 

- Refunded 

## Ticket Module 

Generate: 

- QR Code 

- Booking Number 

- Movie 

- Showtime 

- Hall 

- Seat Numbers 

- Amount 

Format: 

#### PDF 

Booking Number Format: 

CIN-YYYYMMDD-000001 

Example: 

CIN-20260715-000001 

## Notification Module 

Events Booking Created Payment Success Ticket Generated Methods 

- Email 

- In-App Notification 

Future 

- Telegram 

- SMS 

# Admin Panel (Filament) 

## Dashboard 

Cards: 

- Total Revenue 

- Total Bookings 

- Total Customers 

- Total Movies 

Charts: 

- Daily Revenue 

- Monthly Revenue 

- Booking Trends 

## Resources 

MovieResource 

GenreResource 

HallResource 

SeatResource 

ShowtimeResource 

BookingResource 

PaymentResource 

CustomerResource 

TicketResource 

NotificationResource 

# Database Schema 

users 

roles 

permissions 

movies 

genres 

movie_genres 

halls 

seat_types 

seats 

showtimes 

seat_locks 

bookings 

booking_items 

payments 

tickets 

notifications activity_logs failed_jobs 

jobs 

cache 

sessions 

# Service Layer 

Create dedicated services. 

Services: 

MovieService 

ShowtimeService SeatLockService BookingService PaymentService TicketService NotificationService ReportService 

# Action Classes 

LockSeatAction 

UnlockSeatAction 

CreateBookingAction ConfirmBookingAction GenerateTicketAction 

ProcessPaymentAction SendNotificationAction 

# Security Requirements 

Password Hashing 

CSRF Protection 

Role-Based Authorization 

Input Validation Rate Limiting Secure Session Management Activity Logging Payment Verification 

# Performance Requirements 

Page Load Time 

< 2 seconds 

Booking Response 

< 1 second 

Seat Lock Operation 

< 500 ms 

Concurrent Users 100+ 

Availability 

99% 

# Acceptance Criteria 

A customer must be able to: 

- Register 

- Login 

- Browse movies 

- Search movies 

- Filter movies 

- Select showtimes 

- Select seats 

- Receive real-time seat updates 

- Complete payment 

- Download PDF ticket 

- View booking history 

An administrator must be able to: 

- Manage movies 

- Manage halls 

- Manage seats 

- Manage schedules 

- Manage bookings 

- Manage payments 

- View analytics 

The system must guarantee: 

- No double booking 

- Seat locking with expiration 

- Real-time seat synchronization 

- Secure payment workflow 

- Responsive mobile-friendly UI 

- Complete audit trail 

