<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MDS') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex relative">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition.opacity 
             class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 md:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-md transform transition-transform duration-300 md:relative md:translate-x-0 flex flex-col">
            <div class="h-16 flex items-center justify-between px-4 border-b">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-emerald-800 rounded-full flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-emerald-800 leading-tight">দারুল হিকমাহ</span>
                        <span class="text-[12px] font-bold text-emerald-700 leading-tight">আল-ইসলামিয়া মাদ্রাসা</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>ড্যাশবোর্ড (Dashboard)</span>
                </a>
                
                @if(auth()->user()->can('manage academics') || auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('admin.academic-years') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.academic-years') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>শিক্ষাবর্ষ (Academic Year)</span>
                    </a>
                    
                    <a href="{{ route('admin.classes') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.classes') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>শ্রেণী (Classes)</span>
                    </a>
                    
                    <a href="{{ route('admin.sections') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.sections') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>শাখা (Sections)</span>
                    </a>
                    
                    <a href="{{ route('admin.subjects') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.subjects') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>বিষয় (Subjects)</span>
                    </a>
                @endif

                {{-- ── Student Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">শিক্ষার্থী</p>
                </div>
                <a href="{{ route('admin.admissions') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.admissions') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>ভর্তি আবেদন</span>
                </a>
                <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.students*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>শিক্ষার্থী তালিকা</span>
                </a>
                
                {{-- ── Attendance Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">হাজিরা</p>
                </div>
                <a href="{{ route('admin.attendance.mark') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.attendance.mark') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>হাজিরা গ্রহণ</span>
                </a>
                <a href="{{ route('admin.attendance.report') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.attendance.report') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>হাজিরা রিপোর্ট</span>
                </a>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>লগ আউট (Log out)</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 relative">
                <div class="flex items-center">
                    <!-- Hamburger Menu -->
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <!-- Header Title -->
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 truncate">
                        @yield('header', 'Admin Panel')
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 hidden sm:inline-block">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
