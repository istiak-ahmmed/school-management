<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">পরীক্ষার রুটিন</h2>
            <p class="text-sm text-gray-500">আসন্ন এবং চলমান পরীক্ষার সময়সূচী</p>
        </div>
        @if($exams->count() > 0)
            <div class="flex-shrink-0">
                <select wire:model.live="selectedExamId" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm w-full sm:w-64">
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academicYear->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if($exams->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            @php
                $currentExam = $exams->firstWhere('id', $selectedExamId);
            @endphp
            
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $currentExam->name ?? '' }}</h3>
                    <p class="text-sm text-gray-500 flex items-center gap-4 mt-1">
                        <span><strong class="text-gray-700">শুরু:</strong> {{ \Carbon\Carbon::parse($currentExam->start_date)->format('d M, Y') }}</span>
                        <span><strong class="text-gray-700">শেষ:</strong> {{ \Carbon\Carbon::parse($currentExam->end_date)->format('d M, Y') }}</span>
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $currentExam->status->value === \App\Enums\ExamStatus::Ongoing->value ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $currentExam->status->label() }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ ও বার</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিষয় (Subject)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সময় (Time)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রুম (Room)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($routines as $routine)
                            @php
                                $examDate = \Carbon\Carbon::parse($routine->exam_date);
                                $isPast = $examDate->isPast();
                                $isToday = $examDate->isToday();
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isToday ? 'bg-emerald-50/30' : ($isPast ? 'opacity-70 bg-gray-50' : '') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold {{ $isToday ? 'text-emerald-700' : 'text-gray-900' }}">{{ $examDate->format('d M, Y') }}</span>
                                        <span class="text-xs {{ $isToday ? 'text-emerald-600 font-medium' : 'text-gray-500' }}">{{ $examDate->format('l') }}</span>
                                        @if($isToday)
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800 self-start">আজ</span>
                                        @elseif($isPast)
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-200 text-gray-600 self-start">সম্পন্ন</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $routine->subject->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $routine->room ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    এই পরীক্ষার রুটিন এখনও তৈরি হয়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো আসন্ন পরীক্ষা নেই</h3>
            <p class="text-sm text-gray-500">বর্তমানে আপনার কোনো আসন্ন বা চলমান পরীক্ষা নেই।</p>
        </div>
    @endif
</div>
