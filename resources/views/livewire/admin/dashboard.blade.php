@section('header', 'অ্যাডমিন ড্যাশবোর্ড')

<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-cyan-600 to-blue-700 rounded-2xl shadow-lg p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">স্বাগতম, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-cyan-100 text-sm sm:text-base">স্কুল ম্যানেজমেন্ট সিস্টেমের এডমিন ড্যাশবোর্ডে আপনাকে স্বাগতম। নিচে আজকের আপডেট ও ওভারভিউ দেয়া হলো।</p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-1/4 translate-y-1/4">
            <svg class="w-48 h-48 sm:w-64 sm:h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">মোট শিক্ষার্থী</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalStudents) }}</h3>
            </div>
        </div>

        <!-- Teachers & Staff -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">শিক্ষক ও স্টাফ</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalTeachers + $totalStaff) }}</h3>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <span class="text-2xl font-bold">৳</span>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">মাসিক আয় ({{ Carbon\Carbon::now()->format('M') }})</p>
                <h3 class="text-2xl font-bold text-gray-800">৳{{ number_format($monthlyRevenue) }}</h3>
            </div>
        </div>

        <!-- Monthly Expense -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">মাসিক ব্যয় ({{ Carbon\Carbon::now()->format('M') }})</p>
                <h3 class="text-2xl font-bold text-gray-800">৳{{ number_format($monthlyExpense) }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Attendance & Quick Links -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Attendance Overview -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        আজকের উপস্থিতি
                    </h3>
                </div>
                <div class="p-5 space-y-6">
                    <!-- Student -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-600">শিক্ষার্থী</span>
                            <span class="text-sm font-bold text-gray-800">{{ $studentAttendanceRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $studentAttendanceRate }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Teacher & Staff -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-600">শিক্ষক ও স্টাফ</span>
                            <span class="text-sm font-bold text-gray-800">{{ $employeeAttendanceRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $employeeAttendanceRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        কুইক অ্যাকশন
                    </h3>
                </div>
                <div class="p-2 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.students.admission') }}" class="p-4 flex flex-col items-center justify-center gap-2 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-center">নতুন ভর্তি</span>
                    </a>
                    
                    <a href="{{ route('admin.finance.fee-collection') }}" class="p-4 flex flex-col items-center justify-center gap-2 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-center">ফি সংগ্রহ</span>
                    </a>

                    <a href="{{ route('admin.attendance.mark') }}" class="p-4 flex flex-col items-center justify-center gap-2 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="w-10 h-10 bg-cyan-50 text-cyan-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-center">হাজিরা</span>
                    </a>

                    <a href="{{ route('admin.communication.sms') }}" class="p-4 flex flex-col items-center justify-center gap-2 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-center">এসএমএস</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activities -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        সাম্প্রতিক ফি কালেকশন
                    </h3>
                    <a href="{{ route('admin.finance.fee-collection') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">সব দেখুন &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium">
                            <tr>
                                <th class="px-5 py-3 rounded-bl-lg">শিক্ষার্থী</th>
                                <th class="px-5 py-3">ভাউচার নং</th>
                                <th class="px-5 py-3">তারিখ</th>
                                <th class="px-5 py-3 text-right">পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentFees as $fee)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                {{ substr($fee->student->user->name ?? 'N', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $fee->student->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500">স্টুডেন্ট আইডি: {{ $fee->student->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600">{{ $fee->payment_no }}</td>
                                    <td class="px-5 py-4 text-gray-600">
                                        <div class="text-sm">{{ $fee->created_at->format('d M, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $fee->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-xs">
                                            ৳{{ number_format($fee->amount_paid, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                                        কোনো সাম্প্রতিক ফি কালেকশন পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


