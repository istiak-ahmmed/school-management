<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Hind Siliguri', sans-serif; }
            .theme-bg { background-color: #065f46; } /* emerald-800 */
            .theme-text { color: #065f46; }
            .theme-border { border-color: #065f46; }
            .gold-bg { background-color: #d97706; } /* amber-600 */
            .gold-text { color: #d97706; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 selection:bg-emerald-200 selection:text-emerald-900">
            <div class="mb-6 flex flex-col items-center">
                <a href="/" wire:navigate class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 theme-bg rounded-full flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="text-center mt-2">
                        <h1 class="text-2xl font-bold theme-text leading-tight">দারুল হিকমাহ আল-ইসলামিয়া মাদ্রাসা</h1>
                        <p class="text-sm text-gray-500 font-medium mt-1">দ্বীনি ও আধুনিক শিক্ষার সমন্বয়</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </body>
</html>
