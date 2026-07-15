<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ফি এর ধরণ (Fee Types)</h2>
            <p class="text-sm text-gray-500 mt-1">স্কুলের যাবতীয় ফি এর নাম ও ফ্রিকোয়েন্সি সেটআপ করুন</p>
        </div>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            নতুন ফি যোগ করুন
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
                        <th class="px-5 py-3 font-medium">ফি এর নাম</th>
                        <th class="px-5 py-3 font-medium text-center">কোড</th>
                        <th class="px-5 py-3 font-medium text-center">আদায় প্রক্রিয়া</th>
                        <th class="px-5 py-3 font-medium text-center">ফ্রিকোয়েন্সি</th>
                        <th class="px-5 py-3 font-medium text-center">স্ট্যাটাস</th>
                        <th class="px-5 py-3 font-medium text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($feeTypes as $type)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-800 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-5 py-3 text-gray-800 font-bold">
                                {{ $type->name }}
                            </td>
                            <td class="px-5 py-3 text-center text-gray-600 font-mono text-sm">
                                {{ $type->code }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($type->is_recurring)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">রিকারিং (Recurring)</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">এককালীন (One-Time)</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-gray-600">
                                {{ $type->frequency->label() }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <button wire:click="toggleStatus({{ $type->id }})" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition {{ $type->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $type->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}
                                </button>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <button wire:click="edit({{ $type->id }})" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500 font-medium">
                                কোনো ফি এর ধরণ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 mx-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">{{ $isEditMode ? 'ফি আপডেট করুন' : 'নতুন ফি যোগ করুন' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ফি এর নাম <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: মাসিক বেতন / পরীক্ষার ফি">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">কোড (ইংরেজিতে) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase" placeholder="যেমন: TUITION_FEE">
                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <input type="checkbox" id="is_recurring" wire:model.live="is_recurring" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <label for="is_recurring" class="text-sm font-medium text-gray-800 cursor-pointer">এটি কি রিকারিং (বার বার প্রযোজ্য) ফি?</label>
                        </div>
                        
                        @if($is_recurring)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ফ্রিকোয়েন্সি <span class="text-red-500">*</span></label>
                                <select wire:model="frequency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="1">মাসিক (Monthly)</option>
                                    <option value="2">ত্রৈমাসিক (Quarterly)</option>
                                    <option value="3">বার্ষিক (Yearly)</option>
                                </select>
                                @error('frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="hidden">
                                <input type="hidden" wire:model="frequency" value="0">
                            </div>
                            <p class="text-xs text-gray-500 bg-blue-50 p-2 rounded border border-blue-100">এটি এককালীন (One-Time) ফি হিসেবে সেভ হবে (যেমন: ভর্তি ফি, সেশন ফি)।</p>
                        @endif
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
