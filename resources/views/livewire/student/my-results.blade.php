<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">পরীক্ষার ফলাফল</h2>
            <p class="text-sm text-gray-500">আপনার সমস্ত পরীক্ষার ফলাফল এবং মার্কশিট</p>
        </div>
        @if($publishedExams->count() > 0)
            <div class="flex-shrink-0">
                <select wire:model.live="selectedExamId" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm w-full sm:w-64">
                    @foreach($publishedExams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academicYear->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if($publishedExams->count() > 0)
        <!-- Result Summary Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-emerald-600 px-6 py-4 flex flex-col md:flex-row justify-between items-center text-white">
                <div>
                    <h3 class="text-lg font-bold">{{ $publishedExams->firstWhere('id', $selectedExamId)->name ?? '' }}</h3>
                    <p class="text-emerald-100 text-sm">মার্কশিট (Mark Sheet)</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-4">
                    <div class="text-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <p class="text-xs text-emerald-100 uppercase tracking-wider mb-1">প্রাপ্ত নম্বর</p>
                        <p class="text-xl font-bold">{{ $obtainedMarks }} <span class="text-sm font-normal opacity-80">/ {{ $totalMarks }}</span></p>
                    </div>
                    <div class="text-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <p class="text-xs text-emerald-100 uppercase tracking-wider mb-1">জিপিএ (GPA)</p>
                        <p class="text-xl font-bold">{{ $gpa }}</p>
                    </div>
                    <div class="text-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <p class="text-xs text-emerald-100 uppercase tracking-wider mb-1">গ্রেড (Grade)</p>
                        <p class="text-xl font-bold {{ $hasFailed ? 'text-red-300' : 'text-white' }}">{{ $finalGrade }}</p>
                    </div>
                </div>
            </div>

            <!-- Marks Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিষয় (Subject)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">পূর্ণমান (Full Marks)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">পাস মার্ক (Pass Marks)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">প্রাপ্ত নম্বর (Obtained)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">গ্রেড (Grade)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">পয়েন্ট (Point)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($marks as $mark)
                            <tr class="hover:bg-gray-50 {{ ($mark->is_absent || $mark->grade === 'F') ? 'bg-red-50/30' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $mark->subject->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $mark->full_marks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $mark->pass_marks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($mark->is_absent)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            অনুপস্থিত
                                        </span>
                                    @else
                                        <span class="text-sm font-bold {{ $mark->marks_obtained < $mark->pass_marks ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $mark->marks_obtained }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold {{ $mark->grade === 'F' ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $mark->grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold {{ $mark->grade_point == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ number_format($mark->grade_point, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    এই পরীক্ষার কোনো মার্কস এখনও আপলোড করা হয়নি।
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
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">ফলাফল প্রকাশিত হয়নি</h3>
            <p class="text-sm text-gray-500">এখনও পর্যন্ত কোনো পরীক্ষার ফলাফল প্রকাশ করা হয়নি। ফলাফল প্রকাশ হলে এখানে দেখা যাবে।</p>
        </div>
    @endif
</div>
