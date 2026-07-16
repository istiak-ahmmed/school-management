<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">শিক্ষার্থীর হাজিরা গ্রহণ</h2>

        <form wire:submit.prevent="loadStudents" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ <span class="text-red-500">*</span></label>
                <input type="date" wire:model="date" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" max="{{ date('Y-m-d') }}">
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

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-green-700 font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md flex justify-between items-center">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-red-700 font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    @if(!empty($students))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700">শিক্ষার্থীর তালিকা ({{ count($students) }} জন)</h3>
                <button type="button" wire:click="markAllPresent" class="text-sm bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-md hover:bg-emerald-200 transition font-medium border border-emerald-200">
                    সবাই উপস্থিত
                </button>
            </div>
            
            <x-shared.attendance-table :students="$students" :attendanceData="$attendanceData" :date="$date" />
            
            <div class="p-4 border-t bg-gray-50/50 flex justify-end">
                <button type="button" wire:click="saveAttendance" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium shadow-sm flex items-center gap-2" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveAttendance">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </span>
                    <span wire:loading wire:target="saveAttendance" class="animate-spin">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    সংরক্ষণ করুন
                </button>
            </div>
        </div>
    @endif
</div>
