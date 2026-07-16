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
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">অ্যাক্সেস নেই</h3>
            <p class="text-sm text-gray-500">হাজিরা গ্রহণের জন্য আপনাকে ফর্ম টিচার হিসেবে নিয়োগ দিতে হবে। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।</p>
        </div>
    @else
        <!-- Controls -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শাখা (Section)</label>
                    <select wire:model.live="selectedSectionId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm">
                        @foreach($formSections as $section)
                            <option value="{{ $section->id }}">{{ $section->schoolClass->name ?? '' }} - {{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ (Date)</label>
                    <input type="date" wire:model.live="date" max="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <button wire:click="markAll('present')" class="flex-1 px-3 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-200 transition">সকলকে উপস্থিত</button>
                    <button wire:click="markAll('absent')" class="flex-1 px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition">সকলকে অনুপস্থিত</button>
                </div>
            </div>
        </div>

        @if($message)
            <div class="mb-4 p-4 rounded-lg text-sm font-medium {{ $messageType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                {{ $message }}
            </div>
        @endif

        @if($isLoaded && count($students) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        শিক্ষার্থীর তালিকা ({{ count($students) }} জন)
                    </h3>
                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($date)->format('d F, Y') }}</span>
                </div>
                <x-shared.attendance-table :students="$students" :attendanceData="$attendanceData" :date="$date" />
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <button wire:click="saveAttendance" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg shadow-sm transition">
                        <svg wire:loading class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span wire:loading.remove>হাজিরা সংরক্ষণ করুন</span>
                        <span wire:loading>সংরক্ষণ হচ্ছে...</span>
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
