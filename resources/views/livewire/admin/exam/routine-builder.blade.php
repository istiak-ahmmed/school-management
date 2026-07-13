<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">পরীক্ষার রুটিন বিল্ডার</h2>
        @if($exam_id && $class_id && count($routines) > 0)
            <a href="#" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                প্রিন্ট রুটিন
            </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষা নির্বাচন করুন <span class="text-red-500">*</span></label>
                <select wire:model.live="exam_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- পরীক্ষা নির্বাচন করুন --</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academicYear->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী নির্বাচন করুন <span class="text-red-500">*</span></label>
                <select wire:model.live="class_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- শ্রেণী নির্বাচন করুন --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-100">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($exam_id && $class_id)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Routine Form -->
            <div class="lg:col-span-1 bg-white p-5 rounded-xl shadow-sm border border-gray-100 self-start sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">নতুন রুটিন যোগ করুন</h3>
                
                <form wire:submit.prevent="addRoutine" class="space-y-4">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষার তারিখ <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="exam_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @error('exam_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

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
                        <label class="block text-sm font-medium text-gray-700 mb-1">রুম নম্বর / নাম</label>
                        <input type="text" wire:model="room" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">পূর্ণমান <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="full_marks" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('full_marks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">পাস মার্ক <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="pass_marks" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('pass_marks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span>শিক্ষক / গার্ড (একাধিক)</span>
                        </label>
                        <div class="w-full border border-gray-300 rounded-lg px-3 py-2 h-40 overflow-y-auto space-y-1">
                            @foreach($teachers as $teacher)
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-gray-50 rounded transition">
                                    <input type="checkbox" wire:model="selected_teachers" value="{{ $teacher->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="text-sm text-gray-700">{{ $teacher->user->name ?? 'N/A' }} <span class="text-xs text-gray-500 ml-1">({{ $teacher->designation }})</span></span>
                                </label>
                            @endforeach
                        </div>
                        @error('selected_teachers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium transition flex justify-center items-center gap-2 mt-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        রুটিন যোগ করুন
                    </button>
                </form>
            </div>

            <!-- Routine Table -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm">
                                <th class="px-5 py-3 font-medium">তারিখ ও সময়</th>
                                <th class="px-5 py-3 font-medium">বিষয়</th>
                                <th class="px-5 py-3 font-medium">রুম</th>
                                <th class="px-5 py-3 font-medium">মান (পূর্ণ/পাস)</th>
                                <th class="px-5 py-3 font-medium">গার্ড</th>
                                <th class="px-5 py-3 font-medium text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($routines as $routine)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3">
                                        <div class="text-gray-800 font-bold">{{ $routine->exam_date->format('d M, Y') }}</div>
                                        <div class="text-gray-500 text-xs">{{ $routine->start_time->format('h:i A') }} - {{ $routine->end_time->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-gray-800 font-medium">
                                        {{ $routine->subject->name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 text-sm">
                                        {{ $routine->room ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 text-sm">
                                        {{ $routine->full_marks }} / <span class="text-red-500">{{ $routine->pass_marks }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 text-sm">
                                        @foreach($routine->teachers as $teacher)
                                            <span class="inline-block bg-indigo-50 text-indigo-700 text-[10px] px-2 py-0.5 rounded border border-indigo-100 mb-1">{{ $teacher->user->name ?? '' }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <button wire:click="deleteRoutine({{ $routine->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">কোনো রুটিন পাওয়া যায়নি</p>
                                            <p class="text-xs text-gray-400 mt-1">বাম পাশের ফর্ম থেকে নতুন রুটিন যোগ করুন।</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">রুটিন বিল্ডার</h3>
                <p class="text-gray-500 mt-1">অনুগ্রহ করে উপর থেকে পরীক্ষা এবং শ্রেণী নির্বাচন করুন।</p>
            </div>
        </div>
    @endif
</div>
