@props(['students', 'attendanceData', 'date'])

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr class="text-gray-600 text-sm border-b">
                <th class="py-3 px-6 font-semibold w-16 text-center uppercase tracking-wider">রোল</th>
                <th class="py-3 px-6 font-semibold uppercase tracking-wider">শিক্ষার্থীর নাম</th>
                <th class="py-3 px-6 font-semibold uppercase tracking-wider">ভর্তি নং</th>
                <th class="py-3 px-6 font-semibold text-center min-w-[320px] uppercase tracking-wider">উপস্থিতির ধরন</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm bg-white">
            @foreach($students as $student)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 px-6 text-center font-bold text-gray-700">{{ $student->roll_no ?? '-' }}</td>
                    <td class="py-3 px-6">
                        <div class="flex items-center gap-3">
                            @if($student->photo_path)
                                <img src="{{ Storage::url($student->photo_path) }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs border border-emerald-200">
                                    {{ mb_substr($student->user->name ?? $student->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $student->user->name ?? $student->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-gray-500">{{ $student->admission_no ?? '-' }}</td>
                    <td class="py-3 px-6">
                        <div class="flex justify-center gap-2" x-data>
                            @foreach([
                                'present' => ['bg-emerald-100 text-emerald-700 border-emerald-300', 'উপস্থিত'],
                                'absent' => ['bg-red-100 text-red-700 border-red-300', 'অনুপস্থিত'],
                                'late' => ['bg-amber-100 text-amber-700 border-amber-300', 'বিলম্বে'],
                                'excused' => ['bg-blue-100 text-blue-700 border-blue-300', 'ছুটি']
                            ] as $statusKey => [$colors, $label])
                                <label class="cursor-pointer">
                                    <input type="radio" 
                                        wire:model="attendanceData.{{ $student->id }}" 
                                        value="{{ $statusKey }}"
                                        class="sr-only peer">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border transition 
                                        peer-checked:{{ $colors }} 
                                        border-gray-200 text-gray-500 hover:border-gray-300
                                        {{ ($attendanceData[$student->id] ?? '') === $statusKey ? $colors . ' border' : '' }}">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
