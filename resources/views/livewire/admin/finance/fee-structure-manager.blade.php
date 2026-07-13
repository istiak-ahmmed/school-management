<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">ফি স্ট্রাকচার (Fee Structure)</h2>
        <p class="text-sm text-gray-500 mt-1">শ্রেণীভিত্তিক বিভিন্ন ফি এর পরিমাণ নির্ধারণ করুন</p>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 bg-gray-50 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষাবর্ষ</label>
                <select wire:model.live="academic_year_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী</label>
                <select wire:model.live="class_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($academic_year_id && $class_id)
            <div class="p-6">
                <form wire:submit.prevent="save">
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-indigo-50 border-y border-indigo-100 text-indigo-800 text-sm">
                                    <th class="px-5 py-3 font-semibold rounded-l-lg">ফি এর নাম</th>
                                    <th class="px-5 py-3 font-semibold text-center">আদায় প্রক্রিয়া</th>
                                    <th class="px-5 py-3 font-semibold text-right rounded-r-lg w-48">পরিমাণ (৳)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($feeTypes as $type)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-4 text-gray-800 font-medium">
                                            {{ $type->name }}
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @if($type->is_recurring)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $type->frequency->label() }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    এককালীন
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            <input type="number" step="0.01" wire:model="feeAmounts.{{ $type->id }}" class="w-full text-right border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-gray-300" placeholder="0.00">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-12 text-center text-gray-500 font-medium">
                                            কোনো সক্রিয় ফি টাইপ পাওয়া যায়নি। আগে ফি টাইপ তৈরি করুন।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($feeTypes->count() > 0)
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                সংরক্ষণ করুন
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @else
            <div class="p-12 text-center text-gray-500 font-medium">
                ফি স্ট্রাকচার দেখতে শিক্ষাবর্ষ এবং শ্রেণী নির্বাচন করুন।
            </div>
        @endif
    </div>
</div>
