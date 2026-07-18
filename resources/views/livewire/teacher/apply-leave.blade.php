<div class="space-y-6">
    <!-- Leave Overview Cards -->
    @if(count($leaveBalances) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($leaveBalances as $balance)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition duration-200">
                <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4 border-b pb-2">{{ $balance['type']->name }}</h3>
                <div class="grid grid-cols-3 gap-2 text-center divide-x divide-gray-100">
                    <div>
                        <p class="text-xl font-bold text-gray-800">{{ $balance['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">মোট</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-emerald-600">{{ $balance['enjoyed'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">ব্যবহৃত</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-orange-500">{{ $balance['remaining'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">অবশিষ্ট</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">ছুটির আবেদন (Apply Leave)</h2>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ছুটির ধরন <span class="text-red-500">*</span></label>
                    <select wire:model="leave_type_id" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} (সর্বোচ্চ {{ $type->max_days_per_year }} দিন)</option>
                        @endforeach
                    </select>
                    @error('leave_type_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-end pb-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="is_half_day" class="rounded text-emerald-600 focus:ring-emerald-500 mr-2 h-5 w-5">
                        <span class="text-sm font-bold text-gray-700">Half Day (অর্ধদিবস) আবেদন?</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শুরুর তারিখ <span class="text-red-500">*</span></label>
                    <input type="date" wire:model.live="from_date" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('from_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শেষের তারিখ <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="to_date" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" 
                        {{ $is_half_day ? 'disabled' : '' }}>
                    @error('to_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ছুটির কারণ <span class="text-red-500">*</span></label>
                <textarea wire:model="reason" rows="3" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                @error('reason') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium shadow-sm flex items-center gap-2">
                    আবেদন জমা দিন
                </button>
            </div>
        </form>
    </div>

    <!-- Application History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-700">আবেদনের হিস্ট্রি</h3>
        </div>
        
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm border-b">
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">ধরণ</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">তারিখ</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">মোট দিন</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm bg-white">
                    @forelse($applications as $app)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 text-gray-900 whitespace-nowrap">{{ $app->leaveType->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($app->from_date)->format('d M, Y') }} 
                                @if(!$app->is_half_day && $app->from_date != $app->to_date)
                                 - {{ \Carbon\Carbon::parse($app->to_date)->format('d M, Y') }}
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-700">{{ $app->total_days }}</span> দিন
                                @if($app->is_half_day)
                                    <span class="ml-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-orange-100 text-orange-800">Half Day</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($app->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">অপেক্ষমাণ (Pending)</span>
                                @elseif($app->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">অনুমোদিত (Approved)</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 capitalize">{{ $app->status }}</span>
                                @endif
                                @if($app->review_note)
                                    <p class="text-xs text-gray-500 mt-1 italic">Note: {{ $app->review_note }}</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">কোনো আবেদন পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
