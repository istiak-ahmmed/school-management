<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">স্বাগতম, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }} | {{ $teacher->designation ?? 'শিক্ষক' }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Today's Classes -->
        <div class="bg-white rounded-xl shadow-sm border border-violet-100 p-6 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">আজকের ক্লাস</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $todayClasses->count() }}</h3>
                </div>
                <div class="p-2 bg-violet-50 rounded-lg text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">{{ strtolower(now()->format('l')) }} অনুযায়ী</p>
        </div>

        <!-- Pending Marks -->
        <div class="bg-white rounded-xl shadow-sm border {{ $pendingMarksCount > 0 ? 'border-orange-200' : 'border-emerald-100' }} p-6 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">মার্কস এন্ট্রি বাকি</p>
                    <h3 class="text-3xl font-bold {{ $pendingMarksCount > 0 ? 'text-orange-600' : 'text-emerald-600' }}">{{ $pendingMarksCount }}</h3>
                </div>
                <div class="p-2 {{ $pendingMarksCount > 0 ? 'bg-orange-50 text-orange-500' : 'bg-emerald-50 text-emerald-500' }} rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                @if($pendingMarksCount > 0)
                    <a href="{{ route('teacher.marks-entry') }}" class="text-orange-600 hover:underline font-medium">মার্কস এন্ট্রি করুন →</a>
                @else
                    সকল এন্ট্রি সম্পন্ন ✓
                @endif
            </p>
        </div>

        <!-- Form Teacher Card -->
        @if($isFormTeacher)
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">ফর্ম টিচার</p>
                        <h3 class="text-xl font-bold text-blue-700">
                            @foreach($formTeacherSections as $sec)
                                {{ $sec->schoolClass->name ?? '' }} - {{ $sec->name }}@if(!$loop->last), @endif
                            @endforeach
                        </h3>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>
                <p class="text-xs mt-3">
                    <a href="{{ route('teacher.attendance') }}" class="text-blue-600 hover:underline font-medium flex items-center">
                        হাজিরা গ্রহণ করুন →
                    </a>
                </p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">মোট পরীক্ষা</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $ongoingExamsCount }}</h3>
                    </div>
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">সক্রিয় / চলমান পরীক্ষা</p>
            </div>
        @endif

        <!-- Assigned Classes Count -->
        <div class="bg-white rounded-xl shadow-sm border border-violet-100 p-6 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">নিয়োজিত ক্লাস</p>
                    <h3 class="text-3xl font-bold text-violet-700">{{ count($assignedClassIds) }}</h3>
                </div>
                <div class="p-2 bg-violet-50 rounded-lg text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <p class="text-xs mt-3">
                <a href="{{ route('teacher.my-classes') }}" class="text-violet-600 hover:underline font-medium">বিস্তারিত দেখুন →</a>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Class Routine -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">আজকের ক্লাস রুটিন</h3>
                    <a href="{{ route('teacher.class-routine') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">সম্পূর্ণ রুটিন</a>
                </div>
                @if($todayClasses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">সময়</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ক্লাস / শাখা</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বিষয়</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">রুম</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($todayClasses as $routine)
                                    <tr class="hover:bg-violet-50/20 transition">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-gray-700">
                                            {{ Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $routine->schoolClass->name ?? '' }}</span>
                                            <span class="text-xs text-gray-500 ml-1">- {{ $routine->section->name ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="text-sm font-bold text-violet-700">{{ $routine->subject->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $routine->room ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="font-medium">আজকে কোনো ক্লাস নেই।</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming Exams -->
            @if($upcomingExams->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">আসন্ন পরীক্ষা</h3>
                        <a href="{{ route('teacher.exam-routine') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">বিস্তারিত</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($upcomingExams as $exam)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col items-center justify-center bg-violet-50 text-violet-700 rounded-lg p-2 min-w-[56px] text-center border border-violet-100">
                                        <span class="text-xs font-semibold uppercase">{{ $exam->exam_date->format('M') }}</span>
                                        <span class="text-xl font-bold leading-none my-1">{{ $exam->exam_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $exam->subject->name ?? '' }}</h4>
                                        <p class="text-xs text-gray-500">{{ $exam->exam->name ?? '' }} | {{ $exam->schoolClass->name ?? '' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-gray-500">{{ $exam->room }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Notices Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">নোটিশ বোর্ড</h3>
                    <a href="{{ route('teacher.notices') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium">সব দেখুন</a>
                </div>
                @forelse($notices as $notice)
                    <div class="p-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium bg-violet-50 text-violet-700 px-2 py-0.5 rounded">{{ ucfirst($notice->category) }}</span>
                            <span class="text-xs text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                            @if($notice->is_pinned)
                                <svg class="w-3.5 h-3.5 text-orange-500 mr-1.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            @endif
                            {{ $notice->title }}
                        </h4>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $notice->body }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">নতুন কোনো নোটিশ নেই।</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
