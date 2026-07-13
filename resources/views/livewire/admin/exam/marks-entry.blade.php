<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">মার্কস এন্ট্রি</h2>
        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            CSV ইমপোর্ট
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষা <span class="text-red-500">*</span></label>
                <select wire:model.live="exam_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span class="text-red-500">*</span></label>
                <select wire:model.live="class_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শাখা (ঐচ্ছিক)</label>
                <select wire:model.live="section_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" {{ empty($sections) ? 'disabled' : '' }}>
                    <option value="">সকল শাখা</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">বিষয় <span class="text-red-500">*</span></label>
                <select wire:model.live="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" {{ empty($subjects) ? 'disabled' : '' }}>
                    <option value="">নির্বাচন করুন</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="mb-4 bg-yellow-50 text-yellow-700 p-4 rounded-lg border border-yellow-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('warning') }}
        </div>
    @endif

    @if($routine && count($students) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-indigo-50 border-b border-indigo-100 p-4 flex justify-between items-center">
                <div class="text-sm font-medium text-indigo-800">
                    পূর্ণমান: <strong>{{ $routine->full_marks }}</strong> | পাস মার্ক: <strong>{{ $routine->pass_marks }}</strong>
                </div>
                <button wire:click="saveMarks" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    মার্কস সংরক্ষণ করুন
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm">
                            <th class="px-5 py-3 font-medium">রোল নং</th>
                            <th class="px-5 py-3 font-medium">শিক্ষার্থীর নাম</th>
                            <th class="px-5 py-3 font-medium">শাখা</th>
                            <th class="px-5 py-3 font-medium text-center w-32">অনুপস্থিত?</th>
                            <th class="px-5 py-3 font-medium w-48">প্রাপ্ত নম্বর</th>
                            <th class="px-5 py-3 font-medium text-center w-24">গ্রেড</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition {{ ($marksData[$student->id]['existing'] ?? false) ? 'bg-emerald-50/30' : '' }}">
                                <td class="px-5 py-3 text-gray-600 font-bold">
                                    {{ $student->roll_no }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-gray-800 font-medium">{{ $student->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $student->admission_no }}</div>
                                </td>
                                <td class="px-5 py-3 text-gray-600 text-sm">
                                    {{ $student->section->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="marksData.{{ $student->id }}.is_absent" class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500"></div>
                                    </label>
                                </td>
                                <td class="px-5 py-3">
                                    <input type="number" step="0.01" wire:model="marksData.{{ $student->id }}.marks_obtained" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 {{ ($marksData[$student->id]['is_absent'] ?? false) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : '' }}" 
                                        {{ ($marksData[$student->id]['is_absent'] ?? false) ? 'disabled' : '' }}
                                        placeholder="নম্বর">
                                    @error("marksData.{$student->id}.marks_obtained") 
                                        <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> 
                                    @enderror
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($marksData[$student->id]['grade'])
                                        <span class="inline-block px-2 py-1 bg-gray-100 border border-gray-200 rounded font-bold text-gray-700 text-sm">
                                            {{ $marksData[$student->id]['grade'] }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 border-t border-gray-100 p-4 flex justify-end">
                <button wire:click="saveMarks" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    মার্কস সংরক্ষণ করুন
                </button>
            </div>
        </div>
    @elseif($exam_id && $class_id && $subject_id && count($students) == 0 && !$routine)
        <!-- Handled by error message above -->
    @elseif($exam_id && $class_id && $subject_id && count($students) == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">কোনো শিক্ষার্থী পাওয়া যায়নি</h3>
                <p class="text-gray-500 mt-1">নির্বাচিত শ্রেণীতে কোনো শিক্ষার্থী নেই।</p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">মার্কস এন্ট্রি</h3>
                <p class="text-gray-500 mt-1">উপর থেকে পরীক্ষা, শ্রেণী এবং বিষয় নির্বাচন করুন।</p>
            </div>
        </div>
    @endif
</div>
