<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MDS') }} - Student Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="h-screen overflow-hidden flex relative">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 md:hidden"
            @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-md transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 flex flex-col border-r border-emerald-100">
            <div class="h-16 flex items-center justify-between px-4 border-b border-emerald-100 bg-emerald-50/50">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5">
                    <div
                        class="w-9 h-9 bg-emerald-600 rounded-full flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-emerald-800 leading-tight">শিক্ষার্থী পোর্টাল</span>
                        <span class="text-[12px] font-medium text-emerald-600 leading-tight">Student Portal</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-emerald-500 hover:text-emerald-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            
            <div class="px-4 py-3 border-b border-emerald-50 bg-emerald-50/30 flex items-center gap-3">
                @php
                    $student = auth()->user()->student ?? null;
                    $className = $student && $student->schoolClass ? $student->schoolClass->name : 'N/A';
                    $roll = $student ? $student->roll_no : 'N/A';
                @endphp
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-gray-500 truncate">Class: {{ $className }} | Roll: {{ $roll }}</span>
                </div>
            </div>

            <nav x-init="$nextTick(() => { const active = $el.querySelector('.bg-emerald-50'); if (active) $el.scrollTop = active.offsetTop - ($el.clientHeight / 2); })" class="p-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('student.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.dashboard') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>ড্যাশবোর্ড (Dashboard)</span>
                </a>

                <a href="{{ route('student.profile') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.profile') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span>আমার প্রোফাইল (Profile)</span>
                </a>

                <a href="{{ route('student.attendance') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.attendance') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>হাজিরা (Attendance)</span>
                </a>

                <a href="{{ route('student.class-routine') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.class-routine') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>ক্লাস রুটিন (Class Routine)</span>
                </a>

                <a href="{{ route('student.exam-routine') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.exam-routine') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span>পরীক্ষার রুটিন (Exam Routine)</span>
                </a>

                <a href="{{ route('student.results') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.results') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>পরীক্ষার ফলাফল (Results)</span>
                </a>

                <a href="{{ route('student.fees') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.fees') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span>ফি ও পেমেন্ট (Fees)</span>
                </a>

                <a href="{{ route('student.notices') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition {{ request()->routeIs('student.notices') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    <span>নোটিশ বোর্ড (Notices)</span>
                </a>

                <!-- Logout Button -->
            </nav>
            <div class="p-4 border-t border-gray-100 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>লগ আউট (Log out)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 relative">
                <div class="flex items-center">
                    <!-- Hamburger Menu -->
                    <button @click="sidebarOpen = true" class="text-emerald-600 focus:outline-none md:hidden mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <!-- Header Title -->
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 truncate">
                        @yield('header', 'Student Portal')
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 hidden sm:inline-block font-medium">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 flex-1 overflow-y-auto bg-gray-50/50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
