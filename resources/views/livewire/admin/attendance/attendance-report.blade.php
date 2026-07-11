<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">হাজিরা রিপোর্ট</h2>

        <form wire:submit.prevent="generateReport" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span class="text-red-500">*</span></label>
                <select wire:model.live="selectedClass" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('selectedClass') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শাখা <span class="text-red-500">*</span></label>
                <select wire:model="selectedSection" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" @if(empty($sections)) disabled @endif>
                    <option value="">নির্বাচন করুন</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('selectedSection') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">মাস <span class="text-red-500">*</span></label>
                <input type="month" wire:model="month" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                @error('month') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    রিপোর্ট তৈরি করুন
                </button>
            </div>
        </form>
    </div>

    @if(!empty($students))
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">মোট শিক্ষার্থী</p>
                    <p class="text-xl font-bold text-gray-800">{{ count($students) }}</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">উপস্থিত (মোট)</p>
                    <p class="text-xl font-bold text-gray-800">{{ $summary[1] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">অনুপস্থিত (মোট)</p>
                    <p class="text-xl font-bold text-gray-800">{{ $summary[2] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">বিলম্বে (মোট)</p>
                    <p class="text-xl font-bold text-gray-800">{{ $summary[3] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">হাজিরার হার</p>
                    <p class="text-xl font-bold text-gray-800">
                        @if($summary['total'] > 0)
                            {{ number_format(($summary[1] / $summary['total']) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700">ক্যালেন্ডার ভিউ</h3>
                <div class="flex gap-2">
                    <button type="button" wire:click="exportPdf" class="text-sm bg-red-50 text-red-600 px-3 py-1.5 rounded-md hover:bg-red-100 transition font-medium border border-red-100 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF ডাউনলোড
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs border-b">
                            <th class="py-2 px-3 font-semibold sticky left-0 bg-gray-50 border-r w-12 text-center">রোল</th>
                            <th class="py-2 px-3 font-semibold sticky left-12 bg-gray-50 border-r min-w-[150px]">শিক্ষার্থীর নাম</th>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                <th class="py-2 px-1 font-semibold text-center w-8 border-r">{{ $d }}</th>
                            @endfor
                            <th class="py-2 px-3 font-semibold text-center">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @foreach($students as $student)
                            @php
                                $studentPresent = 0;
                                $studentTotal = 0;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-2 px-3 text-center font-medium text-gray-600 sticky left-0 bg-white border-r">{{ $student->roll_no ?? '-' }}</td>
                                <td class="py-2 px-3 font-medium text-gray-800 sticky left-12 bg-white border-r truncate max-w-[150px]" title="{{ $student->user->name }}">
                                    {{ $student->user->name }}
                                </td>
                                
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $status = $reportData[$student->id][$d] ?? null;
                                        if ($status) $studentTotal++;
                                        if ($status === 1) $studentPresent++;
                                        
                                        $bgColor = 'bg-gray-50';
                                        $text = '-';
                                        $textColor = 'text-gray-300';
                                        
                                        if ($status === 1) {
                                            $bgColor = 'bg-green-100';
                                            $text = 'P';
                                            $textColor = 'text-green-700';
                                        } elseif ($status === 2) {
                                            $bgColor = 'bg-red-100';
                                            $text = 'A';
                                            $textColor = 'text-red-700';
                                        } elseif ($status === 3) {
                                            $bgColor = 'bg-amber-100';
                                            $text = 'L';
                                            $textColor = 'text-amber-700';
                                        } elseif ($status === 4) {
                                            $bgColor = 'bg-blue-100';
                                            $text = 'E';
                                            $textColor = 'text-blue-700';
                                        }
                                    @endphp
                                    <td class="py-1 px-1 border-r text-center">
                                        <div class="w-6 h-6 mx-auto rounded flex items-center justify-center font-bold {{ $bgColor }} {{ $textColor }}">
                                            {{ $text }}
                                        </div>
                                    </td>
                                @endfor
                                
                                <td class="py-2 px-3 text-center font-bold text-gray-700">
                                    @if($studentTotal > 0)
                                        {{ number_format(($studentPresent / $studentTotal) * 100, 0) }}%
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t bg-gray-50/50 flex flex-wrap gap-4 text-xs font-medium text-gray-600">
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span> P = উপস্থিত</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-red-500 inline-block"></span> A = অনুপস্থিত</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-500 inline-block"></span> L = বিলম্বে</div>
                <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-blue-500 inline-block"></span> E = ছুটি</div>
            </div>
        </div>
    @endif
</div>
