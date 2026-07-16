@props(['students', 'marksData', 'routine', 'color' => 'indigo'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="bg-{{ $color }}-50 border-b border-{{ $color }}-100 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div class="text-sm font-medium text-{{ $color }}-800">
            পূর্ণমান: <strong>{{ $routine->full_marks }}</strong> | পাস মার্ক: <strong>{{ $routine->pass_marks }}</strong>
        </div>
        <button wire:click="saveMarks" wire:loading.attr="disabled" class="bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white px-5 py-2 rounded-lg font-medium transition flex items-center gap-2 shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            <span wire:loading.remove wire:target="saveMarks">মার্কস সংরক্ষণ করুন</span>
            <span wire:loading wire:target="saveMarks">সংরক্ষণ হচ্ছে...</span>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm w-24">রোল</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">শিক্ষার্থীর নাম</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm text-center w-32">অনুপস্থিত?</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm text-center w-48">প্রাপ্ত নম্বর / {{ $routine->full_marks }}</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm text-center w-24">গ্রেড</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($students as $student)
                    <tr class="hover:bg-gray-50 transition {{ isset($marksData[$student->id]['is_absent']) && $marksData[$student->id]['is_absent'] ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold text-sm">
                                {{ $student->roll_no }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $student->user->name ?? $student->name }}</div>
                            <div class="text-xs text-gray-500">{{ $student->admission_no ?? '' }}</div>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="marksData.{{ $student->id }}.is_absent" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <input type="number"
                                wire:model="marksData.{{ $student->id }}.marks_obtained"
                                min="0"
                                max="{{ $routine->full_marks }}"
                                step="0.5"
                                class="w-full text-center rounded-lg border-gray-300 shadow-sm focus:border-{{ $color }}-500 focus:ring-{{ $color }}-500 text-sm disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition"
                                {{ isset($marksData[$student->id]['is_absent']) && $marksData[$student->id]['is_absent'] ? 'disabled' : '' }}
                                placeholder="0">
                            @error("marksData.{$student->id}.marks_obtained")
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if(isset($marksData[$student->id]['is_absent']) && $marksData[$student->id]['is_absent'])
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">ABS</span>
                            @elseif(!empty($marksData[$student->id]['grade']))
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $marksData[$student->id]['grade'] === 'F' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $marksData[$student->id]['grade'] }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
        <button wire:click="saveMarks" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-6 py-2 bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white font-medium rounded-lg shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            <span wire:loading.remove wire:target="saveMarks">মার্কস সংরক্ষণ করুন</span>
            <span wire:loading wire:target="saveMarks">সংরক্ষণ হচ্ছে...</span>
        </button>
    </div>
</div>
