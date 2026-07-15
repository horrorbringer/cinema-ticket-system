<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Cinema Ticketing') }} — Book Your Movie Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes film-grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-1px, 1px); }
            20% { transform: translate(1px, -1px); }
            30% { transform: translate(-1px, -1px); }
            40% { transform: translate(1px, 1px); }
            50% { transform: translate(-1px, 0); }
            60% { transform: translate(1px, 1px); }
            70% { transform: translate(0, -1px); }
            80% { transform: translate(-1px, 0); }
            90% { transform: translate(1px, -1px); }
        }
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .film-grain::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }
        .scanline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.08), transparent);
            animation: scanline 4s linear infinite;
            pointer-events: none;
            z-index: 2;
        }
        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-fade-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-fade-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-fade-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-fade-up-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .animate-fade-up-delay-5 { animation-delay: 0.5s; opacity: 0; }
        .animate-fade-up-delay-6 { animation-delay: 0.6s; opacity: 0; }
        .marquee-track {
            display: flex;
            animation: marquee 30s linear infinite;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .movie-poster-glow {
            box-shadow: 0 0 30px rgba(245, 48, 3, 0.15), 0 8px 32px rgba(0, 0, 0, 0.4);
        }
        .gradient-text {
            background: linear-gradient(135deg, #f5af19, #f12711);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f12711, #f5af19);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(241, 39, 17, 0.35);
        }
        .btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }
        .section-divider {
            background: linear-gradient(90deg, transparent, rgba(245, 48, 3, 0.3), transparent);
            height: 1px;
        }
        .footer-link {
            transition: color 0.2s ease;
        }
        .footer-link:hover {
            color: #f5af19;
        }
        .star-rating {
            color: #f5af19;
        }
        .price-card {
            transition: all 0.3s ease;
        }
        .price-card:hover {
            transform: translateY(-8px);
        }
        .price-card.featured {
            border: 1px solid rgba(245, 48, 3, 0.4);
            background: linear-gradient(180deg, rgba(245, 48, 3, 0.08), transparent);
        }
        .testimonial-card {
            transition: all 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .pill-nav {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .page-wrapper {
            background: #0a0a0a;
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white overflow-x-hidden page-wrapper">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="pill-nav rounded-2xl px-4 sm:px-6 py-3 flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#f12711] to-[#f5af19] flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-wide">CINEMA<span class="text-[#f5af19]">TIX</span></span>
                </a>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('movies.index') }}" class="text-white/70 hover:text-white transition-colors text-sm font-medium">Now Showing</a>
                    <a href="#experiences" class="text-white/70 hover:text-white transition-colors text-sm font-medium">Experiences</a>
                    <a href="#pricing" class="text-white/70 hover:text-white transition-colors text-sm font-medium">Pricing</a>
                    <a href="#contact" class="text-white/70 hover:text-white transition-colors text-sm font-medium">Contact</a>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex text-white/70 hover:text-white text-sm transition-colors">Dashboard</a>
                        <a href="{{ route('bookings.history') }}" class="btn-primary text-white px-5 py-2 rounded-xl text-sm font-semibold shadow-lg">
                            My Bookings
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-white/70 hover:text-white text-sm transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-5 py-2 rounded-xl text-sm font-semibold shadow-lg">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden film-grain">
        <div class="scanline absolute inset-0 z-10"></div>

        <!-- Cinematic background layers -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1a0000] via-[#0d0d0d] to-[#000033] opacity-90"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent"></div>
            <!-- Spotlight effects -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#f12711]/10 rounded-full blur-[120px]"></div>
            <div class="absolute top-1/3 right-1/4 w-80 h-80 bg-[#f5af19]/8 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-1/4 left-1/3 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px]"></div>
        </div>

        <!-- Film strip decoration -->
        <div class="absolute left-0 top-0 bottom-0 w-16 opacity-20 hidden lg:block">
            <div class="h-full flex flex-col justify-around py-20 px-4">
                @foreach(range(1, 12) as $i)
                    <div class="w-8 h-6 bg-white/10 rounded-sm"></div>
                @endforeach
            </div>
        </div>
        <div class="absolute right-0 top-0 bottom-0 w-16 opacity-20 hidden lg:block">
            <div class="h-full flex flex-col justify-around py-20 px-4">
                @foreach(range(1, 12) as $i)
                    <div class="w-8 h-6 bg-white/10 rounded-sm"></div>
                @endforeach
            </div>
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="animate-fade-up animate-fade-up-delay-1">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-medium bg-white/10 backdrop-blur-sm border border-white/10 text-white/80 tracking-wider uppercase mb-6">
                            Now Showing Worldwide
                        </span>
                    </div>

                    <h1 class="animate-fade-up animate-fade-up-delay-2 text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.1] mb-6">
                        Where Every
                        <span class="gradient-text">Frame</span>
                        Tells a
                        <span class="gradient-text">Story</span>
                    </h1>

                    <p class="animate-fade-up animate-fade-up-delay-3 text-lg sm:text-xl text-white/70 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                        From Hollywood blockbusters to indie gems — book your seats in seconds and step into the magic of the big screen.
                    </p>

                    <div class="animate-fade-up animate-fade-up-delay-4 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('movies.index') }}" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-xl inline-flex items-center justify-center group">
                            View Now Showing
                            <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#experiences" class="btn-outline text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center justify-center group">
                            Explore Experiences
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="animate-fade-up animate-fade-up-delay-5 grid grid-cols-3 gap-8 mt-12 max-w-md mx-auto lg:mx-0">
                        <div>
                            <div class="text-2xl sm:text-3xl font-bold gradient-text">500+</div>
                            <div class="text-white/50 text-sm mt-1">Movies</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-bold gradient-text">50K+</div>
                            <div class="text-white/50 text-sm mt-1">Happy Patrons</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-bold gradient-text">4K</div>
                            <div class="text-white/50 text-sm mt-1">Screens</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Right - Marquee Posters -->
                <div class="animate-fade-up animate-fade-up-delay-3 hidden lg:block relative">
                    <div class="relative overflow-hidden rounded-2xl">
                        <div class="marquee-track space-x-4">
                            @for ($i = 0; $i < 2; $i++)
                                @foreach($movies->take(6) as $movie)
                                    <div class="flex-shrink-0 w-48">
                                        <div class="aspect-[2/3] rounded-xl overflow-hidden movie-poster-glow bg-white/5">
                                @php $pUrl = $movie->poster ? $movie->poster : 'https://picsum.photos/seed/' . $movie->id . '/300/450'; @endphp
                                    <img src="{{ $pUrl }}" alt="{{ $movie->title }}" class="w-full h-full object-cover" loading="lazy">
                                        </div>
                                        <p class="text-white/80 text-sm font-medium mt-2 truncate">{{ $movie->title }}</p>
                                    </div>
                                @endforeach
                            @endfor
                        </div>
                    </div>

                    <!-- Floating badge -->
                    <div class="absolute -bottom-4 -right-4 glass-card rounded-xl px-5 py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-white font-semibold text-sm">Secure Booking</div>
                                <div class="text-white/50 text-xs">Instant confirmation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-white/20 flex items-start justify-center p-1.5">
                <div class="w-1.5 h-3 rounded-full bg-white/60"></div>
            </div>
        </div>
    </section>

    <!-- Now Showing Section -->
    <section class="relative py-24 px-4 sm:px-6 lg:px-8" id="now-showing">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
                <div>
                    <span class="text-[#f5af19] text-sm font-semibold tracking-widest uppercase">Don't Miss Out</span>
                    <h2 class="text-3xl sm:text-4xl font-bold mt-2">
                        Now <span class="gradient-text">Showing</span>
                    </h2>
                    <p class="text-white/60 mt-2 max-w-lg">Catch the latest blockbusters and critically acclaimed films on the big screen.</p>
                </div>
                <a href="{{ route('movies.index') }}" class="mt-4 md:mt-0 inline-flex items-center text-white/70 hover:text-white text-sm font-medium transition-colors group">
                    View All Movies
                    <svg class="ml-1 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                @forelse($movies->take(10) as $movie)
                    <a href="{{ route('movies.show', $movie) }}" class="group">
                        <div class="aspect-[2/3] rounded-xl overflow-hidden movie-poster-glow bg-white/5 transform group-hover:scale-[1.03] transition-all duration-300">
                            @if($movie->poster)
                                <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-white/5 to-white/10 p-4">
                                    <svg class="w-12 h-12 text-white/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.553a1 1 0 011.447 1.059L11 17.554a1 1 0 01-1.447-.105L3.447 9.506a1 1 0 011.447-1.058L9 11.995l4-1.444zM5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-white/30 text-xs text-center">{{ $movie->title }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <div>
                                    <p class="text-white font-semibold text-sm leading-tight">{{ $movie->title }}</p>
                                    @if($movie->duration)
                                        <p class="text-white/60 text-xs mt-1">{{ floor($movie->duration / 60) }}h {{ $movie->duration % 60 }}m</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p class="text-white/80 text-sm font-medium mt-2 truncate group-hover:text-white transition-colors">{{ $movie->title }}</p>
                        @if($movie->rating)
                            <div class="flex items-center mt-0.5">
                                <span class="star-rating text-xs">★</span>
                                <span class="text-white/50 text-xs ml-1">{{ $movie->rating }}</span>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-16 h-16 mx-auto rounded-full bg-white/5 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <p class="text-white/50">No movies currently showing.</p>
                        <p class="text-white/30 text-sm mt-1">Check back soon for new releases.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <div class="section-divider max-w-7xl mx-auto"></div>

    <!-- How It Works -->
    <section class="relative py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#f5af19] text-sm font-semibold tracking-widest uppercase">Simple Process</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">
                    Book in <span class="gradient-text">Three</span> Easy Steps
                </h2>
                <p class="text-white/60 mt-3 max-w-md mx-auto">From browsing to your seat — it only takes a few taps.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-[#f12711]/20 to-[#f5af19]/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl font-bold gradient-text">01</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">Pick a Movie</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Browse our curated selection of the latest releases and timeless classics.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-[#f12711]/20 to-[#f5af19]/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl font-bold gradient-text">02</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">Choose Your Seat</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Select the perfect spot with our interactive seating map.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-[#f12711]/20 to-[#f5af19]/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl font-bold gradient-text">03</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">Enjoy the Show</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Pay securely and get your tickets instantly — just scan and enter.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider max-w-7xl mx-auto"></div>

    <!-- Premium Experiences -->
    <section class="relative py-24 px-4 sm:px-6 lg:px-8" id="experiences">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#f5af19] text-sm font-semibold tracking-widest uppercase">Premium Offerings</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">
                    Elevate Your <span class="gradient-text">Experience</span>
                </h2>
                <p class="text-white/60 mt-3 max-w-md mx-auto">Choose from our range of immersive cinema technologies.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass-card rounded-2xl p-8 group hover:bg-white/[0.08] transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/30 to-purple-500/10 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM5 15h14l-4.5-6-3.5 4.51-2.5-3.01L5 15z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold text-xl mb-2">IMAX</h3>
                    <p class="text-white/50 text-sm leading-relaxed mb-4">Laser-projected digital remastering. Crystal-clear imagery on a massive curved screen.</p>
                    <div class="flex items-center text-[#f5af19] text-sm font-medium">
                        From $18.99
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 group hover:bg-white/[0.08] transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/30 to-blue-500/10 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold text-xl mb-2">4DX</h3>
                    <p class="text-white/50 text-sm leading-relaxed mb-4">Motion seats, environmental effects, and atmospheric sensations synced to the film.</p>
                    <div class="flex items-center text-[#f5af19] text-sm font-medium">
                        From $22.99
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 group hover:bg-white/[0.08] transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/30 to-amber-500/10 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold text-xl mb-2">Dolby Atmos</h3>
                    <p class="text-white/50 text-sm leading-relaxed mb-4">Immersive 3D audio that moves around you — every whisper and explosion comes alive.</p>
                    <div class="flex items-center text-[#f5af19] text-sm font-medium">
                        From $15.99
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider max-w-7xl mx-auto"></div>

    <!-- Pricing -->
    <section class="relative py-24 px-4 sm:px-6 lg:px-8" id="pricing">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#f5af19] text-sm font-semibold tracking-widest uppercase">Simple Pricing</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">
                    Plans for <span class="gradient-text">Every</span> Movie Lover
                </h2>
                <p class="text-white/60 mt-3 max-w-md mx-auto">Choose the perfect ticket type for your cinema experience.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="price-card glass-card rounded-2xl p-8 text-center">
                    <h3 class="text-white font-semibold text-lg mb-1">Standard</h3>
                    <p class="text-white/40 text-sm mb-6">Great for a casual outing</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-white">$12</span>
                        <span class="text-white/40 text-sm">/ticket</span>
                    </div>
                    <ul class="space-y-3 mb-8 text-left">
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Standard 2D screening</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Free seat selection</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Digital ticket delivery</span>
                        </li>
                    </ul>
                    <a href="{{ route('movies.index') }}" class="btn-outline text-white w-full py-3 rounded-xl text-sm font-semibold inline-block transition-all">
                        Get Started
                    </a>
                </div>

                <div class="price-card featured glass-card rounded-2xl p-8 text-center relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="px-4 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white">Most Popular</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-1">Premium</h3>
                    <p class="text-white/40 text-sm mb-6">The ultimate experience</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-white">$22</span>
                        <span class="text-white/40 text-sm">/ticket</span>
                    </div>
                    <ul class="space-y-3 mb-8 text-left">
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>IMAX / 4DX screening</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Recliner seating</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Free popcorn & drink</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Priority entry lane</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-primary text-white w-full py-3 rounded-xl text-sm font-semibold inline-block transition-all shadow-lg">
                        Get Started
                    </a>
                </div>

                <div class="price-card glass-card rounded-2xl p-8 text-center">
                    <h3 class="text-white font-semibold text-lg mb-1">Student</h3>
                    <p class="text-white/40 text-sm mb-6">Discounted for students</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-white">$8</span>
                        <span class="text-white/40 text-sm">/ticket</span>
                    </div>
                    <ul class="space-y-3 mb-8 text-left">
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Standard 2D screening</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Valid student ID required</span>
                        </li>
                        <li class="flex items-start space-x-2 text-white/70 text-sm">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <span>Digital ticket delivery</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-outline text-white w-full py-3 rounded-xl text-sm font-semibold inline-block transition-all">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider max-w-7xl mx-auto"></div>

    <!-- Testimonials -->
    <section class="relative py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#f5af19] text-sm font-semibold tracking-widest uppercase">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">
                    What Our <span class="gradient-text">Patrons</span> Say
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="testimonial-card glass-card rounded-2xl p-6">
                    <div class="flex items-center space-x-1 mb-4 star-rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-4">"Seat selection was a breeze and the ticketing was instant. I'll never go back to the old way of buying tickets."</p>
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#f12711]/30 to-[#f5af19]/30 flex items-center justify-center text-xs font-bold text-white/80">SM</div>
                        <div>
                            <p class="text-white font-medium text-sm">Sarah M.</p>
                            <p class="text-white/40 text-xs">Regular Patron</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card glass-card rounded-2xl p-6">
                    <div class="flex items-center space-x-1 mb-4 star-rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-4">"The IMAX experience through this platform is unmatched. Booking the best seats has never been easier."</p>
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#f12711]/30 to-[#f5af19]/30 flex items-center justify-center text-xs font-bold text-white/80">JD</div>
                        <div>
                            <p class="text-white font-medium text-sm">James D.</p>
                            <p class="text-white/40 text-xs">Film Enthusiast</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card glass-card rounded-2xl p-6">
                    <div class="flex items-center space-x-1 mb-4 star-rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-4">"As a student, the discounted tickets are a lifesaver. The whole process from browsing to entry is seamless."</p>
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#f12711]/30 to-[#f5af19]/30 flex items-center justify-center text-xs font-bold text-white/80">AK</div>
                        <div>
                            <p class="text-white font-medium text-sm">Alex K.</p>
                            <p class="text-white/40 text-xs">Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-3xl p-10 sm:p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#f12711]/10 to-[#f5af19]/5"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                        Ready for the <span class="gradient-text">Big Screen</span>?
                    </h2>
                    <p class="text-white/60 mb-8 max-w-lg mx-auto">Join thousands of happy moviegoers. Book your next cinematic adventure in seconds.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center justify-center shadow-xl">
                            Create Free Account
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('movies.index') }}" class="btn-outline text-white px-8 py-4 rounded-xl font-semibold text-lg inline-flex items-center justify-center">
                            Browse Movies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/10 px-4 sm:px-6 lg:px-8" id="contact">
        <div class="max-w-7xl mx-auto py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#f12711] to-[#f5af19] flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold">CINEMATIX</span>
                    </div>
                    <p class="text-white/40 text-sm leading-relaxed">Your premier destination for movie ticketing. Book smart, watch big.</p>
                </div>

                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Movies</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('movies.index') }}" class="footer-link text-white/50 text-sm">Now Showing</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Coming Soon</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Top Rated</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Trailers</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Support</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="footer-link text-white/50 text-sm">Help Center</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Refund Policy</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Accessibility</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Contact Us</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Connect</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="footer-link text-white/50 text-sm">Facebook</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Instagram</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">Twitter / X</a></li>
                        <li><a href="#" class="footer-link text-white/50 text-sm">TikTok</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row items-center justify-between">
                <p class="text-white/30 text-xs">&copy; {{ date('Y') }} {{ config('app.name', 'Cinema Ticketing') }}. All rights reserved.</p>
                <div class="flex items-center space-x-6 mt-4 sm:mt-0">
                    <a href="#" class="text-white/30 hover:text-white/50 text-xs transition-colors">Privacy Policy</a>
                    <a href="#" class="text-white/30 hover:text-white/50 text-xs transition-colors">Terms of Service</a>
                    <a href="#" class="text-white/30 hover:text-white/50 text-xs transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
