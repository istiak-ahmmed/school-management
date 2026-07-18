<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">হাজিরা গ্রহণ</h2>
            <p class="text-sm text-gray-500">শিক্ষার্থীদের দৈনিক উপস্থিতি রেকর্ড করুন</p>
        </div>
    </div>

    @if($formSections->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-12 text-center">
            <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">অ্যাক্সেস নেই</h3>
            <p class="text-sm text-gray-500">হাজিরা গ্রহণের জন্য আপনাকে ফর্ম টিচার হিসেবে নিয়োগ দিতে হবে। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।</p>
        </div>
    @else
        <!-- Controls -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form wire:submit.prevent="loadStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শাখা <span class="text-red-500">*</span></label>
                    <select wire:model="selectedSectionId" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                        @foreach($formSections as $section)
                            <option value="{{ $section->id }}">{{ $section->schoolClass->name ?? '' }} - {{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedSectionId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="date" max="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    @error('date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-transparent mb-1 hidden md:block">&nbsp;</label>
                    <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        শিক্ষার্থী খুঁজুন
                    </button>
                </div>
            </form>
        </div>

        @if($message)
            <div class="mb-6 p-4 rounded-lg text-sm font-medium flex justify-between items-center {{ $messageType === 'success' ? 'bg-green-50 text-green-700 border-l-4 border-green-500' : 'bg-red-50 text-red-700 border-l-4 border-red-500' }}">
                <div class="flex items-center">
                    @if($messageType === 'success')
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                    <span>{{ $message }}</span>
                </div>
                <button wire:click="$set('message', '')" class="hover:opacity-75 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if($isLoaded && count($students) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-700">
                        শিক্ষার্থীর তালিকা ({{ count($students) }} জন) - {{ \Carbon\Carbon::parse($date)->format('d F, Y') }}
                    </h3>
                    <button type="button" wire:click="markAll('1')" class="text-sm bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-md hover:bg-emerald-200 transition font-medium border border-emerald-200">
                        সবাই উপস্থিত
                    </button>
                </div>
                <x-shared.attendance-table :students="$students" :attendanceData="$attendanceData" :date="$date" />
                <div class="p-4 border-t bg-gray-50/50 flex justify-end">
                    <button wire:click="saveAttendance" wire:loading.attr="disabled"
                        class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium shadow-sm flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveAttendance">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                        <span wire:loading wire:target="saveAttendance" class="animate-spin">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                        হাজিরা সংরক্ষণ করুন
                    </button>
                </div>
            </div>
        @elseif($isLoaded)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো শিক্ষার্থী নেই</h3>
                <p class="text-sm text-gray-500">নির্বাচিত শাখায় কোনো সক্রিয় শিক্ষার্থী পাওয়া যায়নি।</p>
            </div>
        @endif
    @endif
</div>
