<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $madrashaName = 'দারুল হিকমাহ আল-ইসলামিয়া মাদ্রাসা';
    @endphp

    <title>@yield('title', $madrashaName) - {{ $madrashaName }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'মাদ্রাসার অনলাইন ম্যানেজমেন্ট এবং শিক্ষা কার্যক্রম।')">
    <meta property="og:title" content="@yield('title', $madrashaName)">
    <meta property="og:description" content="@yield('meta_description', 'মাদ্রাসার অনলাইন ম্যানেজমেন্ট এবং শিক্ষা কার্যক্রম।')">
    <meta property="og:type" content="website">

    <!-- Bengali Google Font (Hind Siliguri) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Hind Siliguri', sans-serif; }
        /* Islamic Theme Colors: Emerald & Gold */
        .theme-bg { background-color: #065f46; } /* emerald-800 */
        .theme-text { color: #065f46; }
        .theme-border { border-color: #065f46; }
        .gold-bg { background-color: #d97706; } /* amber-600 */
        .gold-text { color: #d97706; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col selection:bg-emerald-200 selection:text-emerald-900" x-data="{ mobileMenuOpen: false }">

    {{-- ── Top Bar ──────────────────────────────────────────────────────── --}}
    <div class="theme-bg text-emerald-50 text-xs py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> +৮৮০ ১৯০০-০০০০০০</span>
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> info@madrasha.edu.bd</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="/admission" class="font-semibold text-amber-300 hover:text-amber-200 transition bg-emerald-900/50 px-3 py-1 rounded-md">ভর্তি চলছে (Apply Now)</a>
            </div>
        </div>
    </div>

    {{-- ── Main Navigation ──────────────────────────────────────────────── --}}
    <header class="bg-white shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 theme-bg rounded-full flex items-center justify-center text-white shadow-md flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="max-w-[160px] sm:max-w-[200px] md:max-w-[280px]">
                            <h1 class="text-sm sm:text-base lg:text-lg font-bold theme-text leading-tight whitespace-normal">{{ $madrashaName }}</h1>
                            <p class="text-[10px] sm:text-xs text-gray-500 font-medium truncate mt-0.5">দ্বীনি ও আধুনিক শিক্ষার সমন্বয়</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                    @php
                        $links = [
                            '/' => 'হোম',
                            '/about' => 'সম্পর্কে',
                            '/teachers' => 'শিক্ষক ও স্টাফ',
                            '/notices' => 'নোটিশ বোর্ড',
                            '/results' => 'ফলাফল',
                            '/gallery' => 'গ্যালারি',
                            '/contact' => 'যোগাযোগ',
                        ];
                    @endphp
                    @foreach($links as $path => $label)
                        <a href="{{ url($path) }}" class="text-sm font-semibold {{ request()->is(ltrim($path, '/')) || (request()->is('/') && $path == '/') ? 'theme-text border-b-2 theme-border pb-1' : 'text-gray-600 hover:text-emerald-700 transition' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    <div class="flex items-center gap-4 pl-4 xl:pl-6 border-l border-gray-200 ml-2">
                        <a href="/login" class="text-gray-700 hover:text-emerald-700 font-bold text-sm transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            লগইন
                        </a>
                        <a href="/admission-info" class="theme-bg text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-emerald-700 transition transform hover:-translate-y-0.5">
                            ভর্তি তথ্য
                        </a>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Menu -->
        <div x-show="mobileMenuOpen" x-collapse x-cloak class="lg:hidden border-t border-gray-100 bg-white">
            <div class="px-4 pt-2 pb-5 space-y-1">
                @foreach($links as $path => $label)
                    <a href="{{ url($path) }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is(ltrim($path, '/')) || (request()->is('/') && $path == '/') ? 'bg-emerald-50 theme-text' : 'text-gray-700 hover:bg-gray-50 hover:theme-text' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <div class="border-t border-gray-100 mt-4 pt-4 pb-2 space-y-3 px-2">
                    <a href="/login" class="block w-full text-center bg-gray-50 border border-gray-200 text-gray-800 px-5 py-3 rounded-xl text-base font-bold shadow-sm hover:bg-gray-100 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        লগইন (Login)
                    </a>
                    <a href="/admission-info" class="block w-full text-center theme-bg text-white px-5 py-3 rounded-xl text-base font-bold shadow-md">
                        ভর্তি তথ্য
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- ── Main Content ─────────────────────────────────────────────────── --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- ── Footer ───────────────────────────────────────────────────────── --}}
    <footer class="theme-bg text-emerald-50 mt-16 lg:mt-24 border-t-4 border-amber-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-16">
                <!-- About -->
                <div class="space-y-4 lg:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-emerald-800 shadow-md flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white leading-tight">{{ $madrashaName }}</h2>
                    </div>
                    <p class="text-emerald-100/80 text-sm leading-relaxed">
                        একটি আদর্শ ও আধুনিক শিক্ষাপ্রতিষ্ঠান যা দ্বীনি শিক্ষার পাশাপাশি আধুনিক জ্ঞান-বিজ্ঞানে শিক্ষার্থীদের গড়ে তুলতে প্রতিশ্রুতিবদ্ধ।
                    </p>
                </div>
                
                <!-- Links -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">গুরুত্বপূর্ণ লিংক</h3>
                    <ul class="space-y-2 text-sm text-emerald-100/80">
                        <li><a href="/about" class="hover:text-amber-300 transition">আমাদের সম্পর্কে</a></li>
                        <li><a href="/admission" class="hover:text-amber-300 transition">অনলাইন ভর্তি</a></li>
                        <li><a href="/results" class="hover:text-amber-300 transition">ফলাফল যাচাই</a></li>
                        <li><a href="/contact" class="hover:text-amber-300 transition">যোগাযোগ</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">সহায়তা ও নীতি</h3>
                    <ul class="space-y-2 text-sm text-emerald-100/80">
                        <li><a href="/privacy-policy" class="hover:text-amber-300 transition">প্রাইভেসি পলিসি</a></li>
                        <li><a href="/terms" class="hover:text-amber-300 transition">শর্তাবলী</a></li>
                        <li><a href="/login" class="hover:text-amber-300 transition">শিক্ষার্থী পোর্টাল</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">যোগাযোগ</h3>
                    <ul class="space-y-3 text-sm text-emerald-100/80">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>১২৩, ইসলামী সড়ক, ঢাকা, বাংলাদেশ</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+৮৮০ ১৯০০-০০০০০০</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-emerald-700 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-emerald-200/60">
                <p>&copy; {{ date('Y') }} {{ $madrashaName }}। সর্বস্বত্ব সংরক্ষিত।</p>
                <p class="mt-2 md:mt-0">Powered by ERP System</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js Toast Notification -->
    <div x-data="{
             show: false,
             message: '',
             type: 'success',
             init() {
                 window.addEventListener('notify', event => {
                     this.message = event.detail[0].message;
                     this.type = event.detail[0].type;
                     this.show = true;
                     setTimeout(() => { this.show = false; }, 4000);
                 });
             }
         }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border"
         :class="{
             'bg-red-50 border-red-100 text-red-800': type === 'error',
             'bg-green-50 border-green-100 text-green-800': type === 'success'
         }"
         style="display: none;">
        
        <div x-show="type === 'error'" class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        
        <div x-show="type === 'success'" class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <p class="text-sm font-semibold" x-text="message"></p>

        <button @click="show = false" class="ml-2 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

</body>
</html>
