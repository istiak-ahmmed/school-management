<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">অ্যাডমিন রিপোর্টসমূহ (Admin Reports)</h2>
        <p class="text-sm text-gray-500">স্কুলের যাবতীয় তথ্য ও রিপোর্ট এক নজরে</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        {{-- Student Enrollment Report --}}
        <a href="{{ route('admin.reports.student-enrollment') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition">শিক্ষার্থী ভর্তি রিপোর্ট</h3>
                <p class="text-xs text-gray-500">ক্লাস অনুযায়ী ভর্তি হওয়া শিক্ষার্থীর তালিকা</p>
            </div>
        </a>

        {{-- Fee Collection Report --}}
        <a href="{{ route('admin.reports.fee-collection') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:green-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-green-600 transition">ফি সংগ্রহ রিপোর্ট</h3>
                <p class="text-xs text-gray-500">দৈনিক ও মাসিক আয়ের রিপোর্ট</p>
            </div>
        </a>

        {{-- Fee Defaulters Report --}}
        <a href="{{ route('admin.reports.fee-defaulters') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-red-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-red-600 transition">বকেয়া ফি রিপোর্ট</h3>
                <p class="text-xs text-gray-500">শিক্ষার্থীদের বকেয়া ফি ও SMS নোটিফিকেশন</p>
            </div>
        </a>

        {{-- Income vs Expense Summary --}}
        <a href="{{ route('admin.reports.income-expense') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-purple-600 transition">আয়-ব্যয় সারসংক্ষেপ</h3>
                <p class="text-xs text-gray-500">মাসিক/বার্ষিক আয় ও ব্যয়ের তুলনামূলক রিপোর্ট</p>
            </div>
        </a>

        {{-- Attendance Report --}}
        <a href="{{ route('admin.attendance.report') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:blue-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition">উপস্থিতি রিপোর্ট</h3>
                <p class="text-xs text-gray-500">শিক্ষার্থীদের ক্লাস-ভিত্তিক মাসিক উপস্থিতি</p>
            </div>
        </a>

        {{-- Exam Result Analysis --}}
        <a href="{{ route('admin.reports.exam-result') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-amber-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-yellow-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-yellow-600 transition">পরীক্ষার ফলাফল বিশ্লেষণ</h3>
                <p class="text-xs text-gray-500">মেধা তালিকা ও বিষয়ভিত্তিক পাশের হার</p>
            </div>
        </a>

        {{-- Staff & Payroll Report --}}
        <a href="{{ route('admin.reports.staff-payroll') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-cyan-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-cyan-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-cyan-600 transition">কর্মী ও বেতন রিপোর্ট</h3>
                <p class="text-xs text-gray-500">শিক্ষক ও স্টাফদের বেতন পরিশোধের রিপোর্ট</p>
            </div>
        </a>

        {{-- System Audit Log --}}
        <a href="{{ route('admin.reports.audit-log') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-slate-300 transition group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-gray-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-gray-600 transition">অডিট লগ (Audit Log)</h3>
                <p class="text-xs text-gray-500">সিস্টেমের কার্যক্রম ও পরিবর্তনের লগ</p>
            </div>
        </a>

    </div>
</div>
