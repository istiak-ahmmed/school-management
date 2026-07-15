<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">গ্রেডিং সিস্টেম (Grade Rules)</h2>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            নতুন গ্রেড রুল
        </button>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm">
                        <th scope="col" class="px-5 py-3 font-medium text-center cursor-pointer hover:bg-gray-100 text-gray-500 text-xs tracking-wider"  wire:click="sortBy('id')" >
    <div class="flex items-center justify-center space-x-1">
        <span>ক্র: নং</span>
        @if($sortField === 'id')
            @if($sortDirection === 'asc')
                <svg class="w-3 h-3 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            @else
                <svg class="w-3 h-3 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            @endif
        @else
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
        @endif
    </div>
</th>
                        <th class="px-5 py-3 font-medium">শিক্ষাবর্ষ</th>
                        <th class="px-5 py-3 font-medium text-center">লেটার গ্রেড</th>
                        <th class="px-5 py-3 font-medium text-center">জিপিএ (GPA)</th>
                        <th class="px-5 py-3 font-medium text-center">নম্বর সীমা (Min - Max)</th>
                        <th class="px-5 py-3 font-medium">মন্তব্য</th>
                        <th class="px-5 py-3 font-medium text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rules as $rule)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-600 text-center">
                                {{ $rules->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 font-medium">
                                {{ $rule->academicYear ? $rule->academicYear->name : 'সব শিক্ষাবর্ষের জন্য (Global)' }}
                            </td>
                            <td class="px-5 py-3 text-center text-gray-800 font-bold text-lg">
                                {{ $rule->grade }}
                            </td>
                            <td class="px-5 py-3 text-center text-indigo-600 font-bold">
                                {{ number_format($rule->grade_point, 2) }}
                            </td>
                            <td class="px-5 py-3 text-center text-gray-600">
                                {{ $rule->min_marks }} - {{ $rule->max_marks }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-sm">
                                {{ $rule->remarks ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $rule->id }})" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="delete({{ $rule->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">কোনো গ্রেড রুল পাওয়া যায়নি</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rules->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $rules->links() }}
            </div>
        @endif
    </div>

    <!-- Grade Rule CRUD Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 mx-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">{{ $isEditMode ? 'গ্রেড রুল আপডেট করুন' : 'নতুন গ্রেড রুল যোগ করুন' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষাবর্ষ (ঐচ্ছিক)</label>
                            <select wire:model="academic_year_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">সব শিক্ষাবর্ষের জন্য (Global)</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">শিক্ষাবর্ষ নির্বাচন না করলে এটি সব বছরের জন্য প্রযোজ্য হবে।</p>
                            @error('academic_year_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">লেটার গ্রেড <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="grade" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase" placeholder="যেমন: A+">
                            @error('grade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">জিপিএ (GPA) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" wire:model="grade_point" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: 5.0">
                            @error('grade_point') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">সর্বনিম্ন নম্বর (%) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model="min_marks" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: 80">
                            @error('min_marks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">সর্বোচ্চ নম্বর (%) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model="max_marks" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: 100">
                            @error('max_marks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">মন্তব্য (ঐচ্ছিক)</label>
                            <input type="text" wire:model="remarks" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: Outstanding">
                            @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">বাতিল</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                            {{ $isEditMode ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
