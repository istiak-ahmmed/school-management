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
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="h-screen overflow-hidden flex relative">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 md:hidden"
            @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-md transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 flex flex-col print:hidden">
            <div class="h-16 flex items-center justify-between px-4 border-b">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div
                        class="w-9 h-9 bg-emerald-800 rounded-full flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-emerald-800 leading-tight">দারুল হিকমাহ</span>
                        <span class="text-[12px] font-bold text-emerald-700 leading-tight">আল-ইসলামিয়া মাদ্রাসা</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <nav x-init="$nextTick(() => { const active = $el.querySelector('.bg-indigo-50, .bg-emerald-50'); if (active) $el.scrollTop = active.offsetTop - ($el.clientHeight / 2); })" class="p-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>ড্যাশবোর্ড (Dashboard)</span>
                </a>

                @if (auth()->user()->can('manage academics') || auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('admin.academic-years') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.academic-years') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>শিক্ষাবর্ষ (Academic Year)</span>
                    </a>

                    <a href="{{ route('admin.classes') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.classes') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>শ্রেণী (Classes)</span>
                    </a>

                    <a href="{{ route('admin.sections') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.sections') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span>শাখা (Sections)</span>
                    </a>

                    <a href="{{ route('admin.subjects') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.subjects') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <span>বিষয় (Subjects)</span>
                    </a>

                    {{-- ── Academic Module ─────────────────────────────────── --}}
                    <div class="pt-4 pb-1">
                        <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">একাডেমিক</p>
                    </div>
                    <a href="{{ route('admin.academic.class-routine-builder') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.academic.class-routine-builder') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>ক্লাস রুটিন তৈরি</span>
                    </a>

                    {{-- ── Finance & Accounts ─────────────────────────────────── --}}
                    <div class="pt-4 pb-1">
                        <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">হিসাবরক্ষণ
                            (Finance)</p>
                    </div>
                    <a href="{{ route('admin.finance.fee-collection') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition {{ request()->routeIs('admin.finance.fee-collection') ? 'bg-emerald-50 text-emerald-700 font-bold border-l-4 border-emerald-500' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>বেতন সংগ্রহ (Collect Fee)</span>
                    </a>
                    <a href="{{ route('admin.finance.invoices') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.invoices') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>ইনভয়েস জেনারেট</span>
                    </a>
                    <a href="{{ route('admin.finance.fee-types') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.fee-types') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        <span>ফি এর ধরণ (Fee Types)</span>
                    </a>
                    <a href="{{ route('admin.finance.fee-structures') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.fee-structures') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                            </path>
                        </svg>
                        <span>ফি স্ট্রাকচার (Structures)</span>
                    </a>
                @endif

                {{-- ── Student Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">শিক্ষার্থী</p>
                </div>
                <a href="{{ route('admin.admissions') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.admissions') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>ভর্তি আবেদন (Online)</span>
                </a>
                <a href="{{ route('admin.students.admission') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.students.admission') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span>নতুন ভর্তি (Add Student)</span>
                </a>
                <a href="{{ route('admin.students') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.students') && !request()->routeIs('admin.students.admission') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span>শিক্ষার্থী তালিকা (List)</span>
                </a>
                <a href="{{ route('admin.students.promotion') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.students.promotion') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                        </path>
                    </svg>
                    <span>প্রমোশন (Promotion)</span>
                </a>

                {{-- ── Attendance Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">হাজিরা</p>
                </div>
                <a href="{{ route('admin.attendance.mark') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.attendance.mark') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    <span>হাজিরা গ্রহণ</span>
                </a>
                <a href="{{ route('admin.attendance.report') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.attendance.report') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span>হাজিরা রিপোর্ট</span>
                </a>

                {{-- ── Examination & Results ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">পরীক্ষা ও ফলাফল</p>
                </div>
                <a href="{{ route('admin.exams') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.exams') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>পরীক্ষা ব্যবস্থাপনা</span>
                </a>
                <a href="{{ route('admin.exams.grade-rules') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.exams.grade-rules') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span>গ্রেডিং সিস্টেম</span>
                </a>
                <a href="{{ route('admin.exams.routine-builder') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.exams.routine-builder') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>পরীক্ষার রুটিন</span>
                </a>
                <a href="{{ route('admin.exams.marks-entry') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.exams.marks-entry') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    <span>মার্কস এন্ট্রি</span>
                </a>

                {{-- ── Employee Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">শিক্ষক ও স্টাফ</p>
                </div>
                <a href="{{ route('admin.employees') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.employees') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span>কর্মচারী তালিকা (List)</span>
                </a>
                <a href="{{ route('admin.employees.create') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.employees.create') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span>কর্মচারী নিয়োগ (Add)</span>
                </a>

                {{-- ── Finance Management ─────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">অর্থ ও হিসাব</p>
                </div>
                <a href="{{ route('admin.finance.fee-collection') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.fee-collection') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>বেতন সংগ্রহ</span>
                </a>
                <a href="{{ route('admin.finance.invoices') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.invoices') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>ইনভয়েস ব্যবস্থাপনা</span>
                </a>
                <a href="{{ route('admin.finance.salary-payments') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.salary-payments') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span>শিক্ষক/স্টাফ বেতন</span>
                </a>
                <a href="{{ route('admin.finance.expense-categories') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.expense-categories') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <span>খরচের খাত (Categories)</span>
                </a>
                <a href="{{ route('admin.finance.expenses.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.expenses.*') && !request()->routeIs('admin.finance.expenses.report') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>খরচের তালিকা (Expenses)</span>
                </a>
                <a href="{{ route('admin.finance.expenses.report') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.finance.expenses.report') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>খরচের রিপোর্ট (Report)</span>
                </a>

                {{-- ── Communication ──────────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">যোগাযোগ (Communication)</p>
                </div>
                <a href="{{ route('admin.communication.notices') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.communication.notices') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    <span>নোটিশ বোর্ড (Notice Board)</span>
                </a>
                <a href="{{ route('admin.communication.sms') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.communication.sms') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span>এসএমএস পাঠান (Send SMS)</span>
                </a>

                {{-- ── Reports ─────────────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">রিপোর্ট (Reports)</p>
                </div>
                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>সকল রিপোর্ট (All Reports)</span>
                </a>

                {{-- ── Settings ─────────────────────────────────────────── --}}
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">সেটিংস (Settings)</p>
                </div>
                <a href="{{ route('admin.settings.roles') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.settings.roles') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <span>রোল ও পারমিশন (Roles)</span>
                </a>
                <a href="{{ route('admin.settings.payment-methods') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.settings.payment-methods') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span>পেমেন্ট মেথড (Payment Methods)</span>
                </a>
                <a href="{{ route('admin.settings.gateway') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition {{ request()->routeIs('admin.settings.gateway') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>গেটওয়ে সেটআপ (Gateway Setup)</span>
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
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 relative print:hidden">
                <div class="flex items-center">
                    <!-- Hamburger Menu -->
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        function initPickers() {
            flatpickr('input[type="month"]', {
                altInput: true,
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, // defaults to false
                        dateFormat: "Y-m", // defaults to "F Y"
                        altFormat: "Y-F", // e.g. 2026-July
                        theme: "light" // defaults to "light"
                    })
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    instance.element.dispatchEvent(new Event('input', { bubbles: true }));
                    instance.element.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initPickers);
        document.addEventListener('livewire:navigated', initPickers);
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('trigger-print', () => {
                setTimeout(() => {
                    window.print();
                }, 100);
            });
            Livewire.hook('morph.updated', ({ el, component }) => {
                initPickers();
            });
        });
    </script>
</body>

</html>
