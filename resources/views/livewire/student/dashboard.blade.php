<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">স্বাগতম, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-500">শিক্ষার্থী ড্যাশবোর্ড - {{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Attendance Card -->
        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">চলতি মাসের হাজিরা</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $attendancePercentage }}%</h3>
                </div>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
                মোট ক্লাস: {{ $totalDays }} | উপস্থিত: {{ $presentDays }}
            </div>
        </div>

        <!-- Fees Card -->
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">বকেয়া ফি</p>
                    <h3 class="text-3xl font-bold text-red-600">৳{{ number_format($pendingFees, 2) }}</h3>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-red-500 font-medium">
                @if($pendingFees > 0)
                    <a href="{{ route('student.fees') }}" class="hover:underline flex items-center">
                        ফি পরিশোধ করুন 
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <span class="text-emerald-500">কোনো বকেয়া নেই</span>
                @endif
            </div>
        </div>

        <!-- Academic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 flex flex-col justify-between lg:col-span-2 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">শিক্ষার্থীর তথ্য</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $student->name ?? auth()->user()->name }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">শ্রেণী</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $student->schoolClass->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">শাখা</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $student->section->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">রোল নং</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $student->roll_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">আইডি</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $student->admission_no ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Area: Class Routine -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">আজকের ক্লাস রুটিন</h3>
                    <a href="{{ route('student.class-routine') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">সম্পূর্ণ রুটিন</a>
                </div>
                <div class="p-0">
                    @if(count($todayClasses) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">সময়</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বিষয়</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">শিক্ষক</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">রুম</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($todayClasses as $routine)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $routine->subject->name ?? 'N/A' }}</span>
                                            @if($routine->is_break)
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">বিরতি</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $routine->teacher->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 text-right font-medium">
                                            {{ $routine->room ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>আজকে কোনো ক্লাস নেই।</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Exams -->
            @if(count($upcomingExams) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">আসন্ন পরীক্ষা</h3>
                        <a href="{{ route('student.exam-routine') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">বিস্তারিত</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($upcomingExams as $exam)
                            <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-start">
                                    <div class="flex flex-col items-center justify-center bg-emerald-50 text-emerald-700 rounded-lg p-2 min-w-[60px] text-center border border-emerald-100 mr-4">
                                        <span class="text-xs font-semibold uppercase">{{ $exam->exam_date->format('M') }}</span>
                                        <span class="text-xl font-bold leading-none my-1">{{ $exam->exam_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $exam->subject->name ?? 'N/A' }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $exam->exam->name ?? 'N/A' }} | সময়: {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        রুম: {{ $exam->room }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Area: Notices -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">নোটিশ বোর্ড</h3>
                    <a href="{{ route('student.notices') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">সব দেখুন</a>
                </div>
                <div class="p-0">
                    @forelse($notices as $notice)
                        <div class="p-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded">{{ ucfirst($notice->category) }}</span>
                                <span class="text-xs text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                                @if($notice->is_pinned)
                                    <svg class="w-3.5 h-3.5 text-orange-500 mr-1.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                @endif
                                {{ $notice->title }}
                            </h4>
                            <p class="text-xs text-gray-500 line-clamp-2 mt-2">{{ $notice->body }}</p>
                            @if($notice->attachment_path)
                                <div class="mt-3">
                                    <a href="{{ Storage::url($notice->attachment_path) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        অ্যাটাচমেন্ট দেখুন
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500 text-sm">
                            নতুন কোনো নোটিশ নেই।
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
