<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">পরীক্ষার রুটিন</h2>
        <p class="text-sm text-gray-500">আপনার শ্রেণীর পরীক্ষার সময়সূচী</p>
    </div>

    @if($exams->count() > 0)
        <div class="space-y-6">
            @foreach($exams as $exam)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 bg-violet-50/50">
                        <div>
                            <h3 class="text-lg font-bold text-violet-900">{{ $exam->name }}</h3>
                            <p class="text-sm text-violet-600 mt-0.5">
                                {{ \Carbon\Carbon::parse($exam->start_date)->format('d M') }} — {{ \Carbon\Carbon::parse($exam->end_date)->format('d M, Y') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $exam->status->color() }}-100 text-{{ $exam->status->color() }}-800">
                            {{ $exam->status->label() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">তারিখ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বিষয়</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">শ্রেণী</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">সময়</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">পূর্ণমান / পাস মার্ক</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">রুম</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($exam->routines as $routine)
                                    @php
                                        $examDate = \Carbon\Carbon::parse($routine->exam_date);
                                        $isToday = $examDate->isToday();
                                        $isPast = $examDate->isPast() && !$isToday;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition {{ $isToday ? 'bg-violet-50/30' : ($isPast ? 'opacity-70' : '') }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold {{ $isToday ? 'text-violet-700' : 'text-gray-900' }}">
                                                {{ $examDate->format('d M, Y') }}
                                            </div>
                                            <div class="text-xs {{ $isToday ? 'text-violet-600 font-medium' : 'text-gray-500' }}">
                                                {{ $examDate->format('l') }}
                                                @if($isToday) <span class="ml-1 px-1.5 py-0.5 rounded bg-violet-100 text-violet-700 text-[10px] font-bold">আজ</span> @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $routine->subject->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $routine->schoolClass->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} — {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-bold text-gray-900">{{ $routine->full_marks }}</span>
                                            <span class="text-gray-400 text-xs mx-1">/</span>
                                            <span class="text-sm text-gray-600">{{ $routine->pass_marks }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $routine->room ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">এই পরীক্ষার রুটিন এখনো তৈরি হয়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো পরীক্ষার রুটিন নেই</h3>
            <p class="text-sm text-gray-500">আপনার শ্রেণীর কোনো পরীক্ষার রুটিন এখনো তৈরি হয়নি।</p>
        </div>
    @endif
</div>
