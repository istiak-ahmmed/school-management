<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">কর্মী হাজিরা গ্রহণ (Staff Attendance)</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">কর্মীর ধরন</label>
                <select wire:model.live="employeeType" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="teacher">শিক্ষক (Teacher)</option>
                    <option value="staff">স্টাফ (Staff)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ</label>
                <input type="date" wire:model.live="date" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" max="{{ date('Y-m-d') }}">
                @error('date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex justify-between items-center">
            <span class="text-green-700 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md flex justify-between items-center">
            <span class="text-red-700 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(!empty($employees))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700">কর্মীর তালিকা ({{ count($employees) }} জন)</h3>
                <button type="button" wire:click="markAllPresent" class="text-sm bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-md hover:bg-emerald-200 transition font-medium border border-emerald-200">
                    সবাই উপস্থিত
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-gray-600 text-sm border-b">
                            <th class="py-3 px-6 font-semibold uppercase tracking-wider">নাম</th>
                            <th class="py-3 px-6 font-semibold text-center min-w-[350px] uppercase tracking-wider">উপস্থিতির ধরন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm bg-white">
                        @foreach($employees as $emp)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs border border-emerald-200">
                                            {{ mb_substr($emp->user->name ?? $emp->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $emp->user->name ?? $emp->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex justify-center gap-2">
                                        @foreach([
                                            1 => ['peer-checked:bg-emerald-100 peer-checked:text-emerald-700 peer-checked:border-emerald-300', 'উপস্থিত'],
                                            2 => ['peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-300', 'অনুপস্থিত'],
                                            3 => ['peer-checked:bg-amber-100 peer-checked:text-amber-700 peer-checked:border-amber-300', 'বিলম্বে'],
                                            4 => ['peer-checked:bg-blue-100 peer-checked:text-blue-700 peer-checked:border-blue-300', 'ছুটি'],
                                            5 => ['peer-checked:bg-orange-100 peer-checked:text-orange-700 peer-checked:border-orange-300', 'অর্ধদিবস']
                                        ] as $statusKey => [$peerClasses, $label])
                                            <label class="cursor-pointer">
                                                <input type="radio" 
                                                    wire:model="attendanceData.{{ $emp->id }}" 
                                                    value="{{ $statusKey }}"
                                                    class="sr-only peer">
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border transition 
                                                    border-gray-200 text-gray-500 hover:border-gray-300
                                                    {{ $peerClasses }}">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t bg-gray-50/50 flex justify-end">
                <button type="button" wire:click="saveAttendance" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium shadow-sm flex items-center gap-2" wire:loading.attr="disabled">
                    সংরক্ষণ করুন
                </button>
            </div>
        </div>
    @endif
</div>
