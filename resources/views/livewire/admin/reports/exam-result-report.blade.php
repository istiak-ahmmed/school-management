<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">পরীক্ষার ফলাফল বিশ্লেষণ</h2>
            <p class="text-sm text-gray-500">Exam Result Analysis & Merit List</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if($data)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-amber-500 text-white rounded-lg shadow-sm hover:bg-amber-600 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download CSV
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            ফিল্টার করুন (Filters)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Exam -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">পরীক্ষা <span class="text-red-500">*</span></label>
                <select wire:model.live="exam_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="">-- পরীক্ষা নির্বাচন করুন --</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academicYear->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শ্রেণী (Class) <span class="text-red-500">*</span></label>
                <select wire:model.live="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="">-- শ্রেণী নির্বাচন করুন --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শাখা (Section)</label>
                <select wire:model.live="section_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" @if(empty($sections)) disabled @endif>
                    <option value="">-- সকল শাখা --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
        @if(!$exam_id || !$class_id)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p>অনুগ্রহ করে একটি পরীক্ষা এবং শ্রেণী নির্বাচন করুন।</p>
            </div>
        @elseif(!$data)
            <div class="bg-gray-50 border border-gray-200 text-gray-600 p-8 rounded-lg flex flex-col items-center justify-center">
                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-lg font-medium">কোনো ফলাফল পাওয়া যায়নি</p>
                <p class="text-sm">নির্বাচিত শর্ত অনুযায়ী কোনো মার্ক এন্ট্রি করা হয়নি।</p>
            </div>
        @else
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মোট শিক্ষার্থী</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $data['summary']['total_students'] }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">পাস করেছে</p>
                        <p class="text-2xl font-bold text-green-600">{{ $data['summary']['pass_count'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">ফেল করেছে</p>
                        <p class="text-2xl font-bold text-red-600">{{ $data['summary']['fail_count'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-amber-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">পাসের হার</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $data['summary']['pass_percent'] }}%</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Subject Analysis -->
                <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">বিষয়ভিত্তিক বিশ্লেষণ</h3>
                    </div>
                    <div class="p-0 overflow-y-auto max-h-[500px]">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2">বিষয়</th>
                                    <th class="px-4 py-2 text-center">গড়</th>
                                    <th class="px-4 py-2 text-center">পাস %</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($data['subjects'] as $subjName => $subjData)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $subjName }}</td>
                                        <td class="px-4 py-3 text-center">{{ $subjData['average'] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $subjData['pass_percent'] >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $subjData['pass_percent'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Merit List -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">মেধাতালিকা (Merit List)</h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 w-16 text-center">মেধা ক্রম</th>
                                    <th class="px-6 py-3">শিক্ষার্থী</th>
                                    <th class="px-6 py-3 text-center">মোট নম্বর</th>
                                    <th class="px-6 py-3 text-center">CGPA</th>
                                    <th class="px-6 py-3 text-center">গ্রেড</th>
                                    <th class="px-6 py-3 text-center">অবস্থা</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php $pos = 1; @endphp
                                @foreach($data['students'] as $studentRes)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 text-center">
                                            @if($pos == 1)
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-700 rounded-full font-bold">1</span>
                                            @elseif($pos == 2)
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-700 rounded-full font-bold">2</span>
                                            @elseif($pos == 3)
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-800 rounded-full font-bold">3</span>
                                            @else
                                                <span class="font-bold text-gray-500">{{ $pos }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            <div class="font-medium text-gray-800">{{ optional($studentRes['student'])->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">
                                                রোল: {{ optional(optional($studentRes['student'])->currentEnrollment)->roll_no ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-center font-bold text-gray-700">
                                            {{ $studentRes['total_marks'] }}
                                        </td>
                                        <td class="px-6 py-3 text-center font-bold {{ $studentRes['is_fail'] ? 'text-red-500' : 'text-blue-600' }}">
                                            {{ number_format($studentRes['cgpa'], 2) }}
                                        </td>
                                        <td class="px-6 py-3 text-center font-bold {{ $studentRes['is_fail'] ? 'text-red-500' : 'text-green-600' }}">
                                            {{ $studentRes['final_grade'] }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            @if($studentRes['is_fail'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">ফেল</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">পাস</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $pos++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
