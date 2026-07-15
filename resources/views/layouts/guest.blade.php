<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-[#0a0a0a]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#1a0000] via-[#0d0d0d] to-[#000033]">
            <div class="mb-6">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#f12711] to-[#f5af19] flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl tracking-wide">CINEMA<span class="text-[#f5af19]">TIX</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-white/30 text-xs">&copy; {{ date('Y') }} CinemaTix. All rights reserved.</p>
        </div>
    </body>
</html>
