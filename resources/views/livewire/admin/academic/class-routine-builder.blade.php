<div>
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4 print:hidden">
        <h2 class="text-2xl font-bold text-gray-800">ক্লাস রুটিন বিল্ডার</h2>
        @if($class_id)
        <div class="flex gap-2">
            <button wire:click="exportToPdf" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                প্রিন্ট / PDF
            </button>
            <button wire:click="exportToExcel" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </button>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-end print:hidden">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span class="text-red-500">*</span></label>
            <select wire:model.live="class_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">-- শ্রেণী নির্বাচন করুন --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">শাখা (ঐচ্ছিক)</label>
            <select wire:model.live="section_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" {{ empty($sections) ? 'disabled' : '' }}>
                <option value="">-- সকল শাখা / প্রযোজ্য নয় --</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-32">
            <label class="block text-sm font-medium text-gray-700 mb-1">মোট পিরিয়ড</label>
            <input type="number" wire:model.live.debounce.500ms="max_periods" min="1" max="15" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-100 print:hidden">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3 print:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($class_id)
        <!-- Print Header (Only visible during print) -->
        <div class="hidden print:block text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">ক্লাস রুটিন</h2>
            <p class="text-gray-600 mt-1">
                শ্রেণী: {{ collect($classes)->firstWhere('id', $class_id)->name ?? '' }} 
                @if($section_id) | শাখা: {{ collect($sections)->firstWhere('id', $section_id)->name ?? '' }} @endif
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto print:overflow-visible print:shadow-none print:border-none">
            <style>
                @media print {
                    @page { size: landscape; margin: 0.5cm; }
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    /* Make table fit into the page */
                    table { table-layout: fixed !important; width: 100% !important; min-width: 0 !important; }
                    th, td { word-wrap: break-word; overflow-wrap: break-word; white-space: normal !important; }
                    th { width: auto !important; padding: 4px !important; font-size: 12px !important; }
                    td { padding: 2px !important; }
                    
                    /* Scale down the inner blocks */
                    .min-h-\[80px\] { min-height: 50px !important; padding: 4px !important; }
                    .text-sm { font-size: 11px !important; line-height: 1.2 !important; margin-bottom: 2px !important; }
                    .text-xs { font-size: 10px !important; line-height: 1.2 !important; }
                    .text-\[10px\] { font-size: 9px !important; line-height: 1.2 !important; }
                    
                    /* Hide unnecessary UI elements in print cells */
                    svg { display: none !important; }
                    .pr-5 { padding-right: 0 !important; }
                }
            </style>
            <table class="w-full text-center border-collapse min-w-[800px] print:min-w-0 print:table-fixed">
                <thead>
                    <tr>
                        <th class="border px-4 py-3 bg-gray-100 text-gray-700 w-32">দিন / পিরিয়ড</th>
                        @foreach($this->periods as $p)
                            <th class="border px-4 py-3 bg-gray-50 text-gray-600 font-medium w-40">{{ $p }}ম পিরিয়ড</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $dayId => $dayName)
                        <tr>
                            <td class="border px-4 py-6 bg-gray-50 font-bold text-gray-700">{{ $dayName }}</td>
                            
                            @foreach($this->periods as $p)
                                @php
                                    $hasRoutine = isset($routinesMatrix[$dayId][$p]);
                                    $routine = $hasRoutine ? $routinesMatrix[$dayId][$p] : null;
                                @endphp
                                
                                <td class="border p-2 align-top relative group">
                                    @if($hasRoutine)
                                        @if($routine->is_break)
                                            <div wire:click="openModal({{ $dayId }}, {{ $p }})" class="bg-amber-50 border border-amber-200 rounded p-2 cursor-pointer hover:bg-amber-100 transition min-h-[80px] flex flex-col justify-center items-center text-center">
                                                <svg class="w-6 h-6 text-amber-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                <div class="font-bold text-amber-800 text-sm">{{ $routine->note ?: 'বিরতি / টিফিন' }}</div>
                                                <div class="text-[10px] text-amber-600 mt-1">{{ $routine->start_time->format('h:i A') }} - {{ $routine->end_time->format('h:i A') }}</div>
                                            </div>
                                        @else
                                            <div wire:click="openModal({{ $dayId }}, {{ $p }})" class="bg-indigo-50 border border-indigo-100 rounded p-2 cursor-pointer hover:bg-indigo-100 transition min-h-[80px] flex flex-col justify-center relative">
                                                @if($routine->is_combined)
                                                    <span class="absolute top-1 right-1 bg-purple-100 text-purple-700 text-[9px] font-bold px-1.5 py-0.5 rounded" title="যৌথ ক্লাস">যৌথ</span>
                                                @endif
                                                <div class="font-bold text-indigo-800 text-sm mb-1 pr-5">{{ $routine->subject->name ?? '' }}</div>
                                                <div class="text-xs text-indigo-600 font-medium">{{ $routine->teacher->user->name ?? '' }}</div>
                                                <div class="text-[10px] text-gray-500 mt-1">{{ $routine->start_time->format('h:i A') }} - {{ $routine->end_time->format('h:i A') }}</div>
                                                @if($routine->room)
                                                    <div class="text-[10px] text-gray-400">রুম: {{ $routine->room }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div wire:click="openModal({{ $dayId }}, {{ $p }})" class="border-2 border-dashed border-gray-200 rounded p-2 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition min-h-[80px] flex items-center justify-center text-gray-400 group-hover:text-indigo-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">রুটিন গ্রিড</h3>
                <p class="text-gray-500 mt-1">দয়া করে শ্রেণী নির্বাচন করুন।</p>
            </div>
        </div>
    @endif

    <!-- Routine Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 mx-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">{{ $routineId ? 'পিরিয়ড আপডেট করুন' : 'নতুন পিরিয়ড যোগ করুন' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="mb-4 px-3 py-2 bg-gray-50 rounded-lg text-sm text-gray-600 flex justify-between">
                    <span><strong>দিন:</strong> {{ $days[$day_of_week] ?? '' }}</span>
                    <span><strong>পিরিয়ড:</strong> {{ $period_no }}</span>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 gap-4">
                        
                        <!-- Checkboxes for Enhancements -->
                        <div class="flex items-center gap-6 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="is_break" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-sm font-medium text-gray-700">এটি কি বিরতি/টিফিন?</span>
                            </label>
                            
                            @if(!$is_break)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="is_combined" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <span class="text-sm font-medium text-purple-700">যৌথ ক্লাস (Combined Class)</span>
                            </label>
                            @endif
                        </div>

                        @if($is_break)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">বিরতির নাম (ঐচ্ছিক)</label>
                                <input type="text" wire:model="note" placeholder="যেমন: টিফিন, নামাযের বিরতি..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">বিষয় <span class="text-red-500">*</span></label>
                                <select wire:model="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">নির্বাচন করুন</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষক <span class="text-red-500">*</span></label>
                                <select wire:model="teacher_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">নির্বাচন করুন</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? '' }} ({{ $teacher->designation }})</option>
                                    @endforeach
                                </select>
                                @error('teacher_id') 
                                    <span class="text-red-500 text-xs flex items-start gap-1 mt-1">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $message }}
                                    </span> 
                                @enderror
                            </div>
                        @endif

                        @if($is_combined && !$is_break)
                            <div class="bg-purple-50 p-3 rounded-lg border border-purple-100">
                                <label class="block text-sm font-medium text-purple-800 mb-1">অতিরিক্ত শাখা সমূহ (Combined with)</label>
                                <p class="text-[10px] text-purple-600 mb-2">এই ক্লাসটি আর কোন কোন শাখার সাথে একত্রে হবে তা নির্বাচন করুন।</p>
                                <select wire:model="additional_sections" multiple class="w-full border border-purple-300 rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 h-24 text-sm">
                                    @foreach($all_sections as $sec)
                                        @if($sec->id != $section_id)
                                            <option value="{{ $sec->id }}">{{ $sec->schoolClass->name ?? '' }} - {{ $sec->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">একাধিক নির্বাচন করতে Ctrl চেপে ক্লিক করুন।</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">শুরুর সময় <span class="text-red-500">*</span></label>
                                <input type="time" wire:model="start_time" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">শেষের সময় <span class="text-red-500">*</span></label>
                                <input type="time" wire:model="end_time" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">রুম নম্বর (ঐচ্ছিক)</label>
                            <input type="text" wire:model="room" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between items-center border-t pt-4">
                        <div>
                            @if($routineId)
                                <button type="button" wire:click="delete" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-500 hover:text-red-700 text-sm font-medium transition">মুছে ফেলুন</button>
                            @endif
                        </div>
                        <div class="flex gap-3">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">বাতিল</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
