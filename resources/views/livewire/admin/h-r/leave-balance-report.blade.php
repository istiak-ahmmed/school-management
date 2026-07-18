<div class="space-y-6">
    <div class="flex justify-between items-center bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-lg font-bold text-gray-800">ছুটির ব্যালেন্স রিপোর্ট (Leave Balance Report)</h2>
        <div>
            <select wire:model.live="employeeType" class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="all">সবাই (All)</option>
                <option value="teacher">শিক্ষক (Teacher)</option>
                <option value="staff">স্টাফ (Staff)</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm border-b">
                        <th class="py-4 px-4 font-bold uppercase tracking-wider whitespace-nowrap bg-gray-50 sticky left-0 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] z-20 border-r border-gray-200">এমপ্লয়ি (Employee)</th>
                        @foreach($leaveTypes as $type)
                            @if($employeeType === 'all' || $type->applicable_to === 'both' || $type->applicable_to === $employeeType)
                                <th class="py-4 px-4 font-bold uppercase tracking-wider whitespace-nowrap text-center border-l border-gray-200">
                                    {{ $type->name }}<br>
                                    <span class="text-xs text-gray-500 font-normal">ব্যবহৃত / মোট</span>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm bg-white">
                    @forelse($reportData as $data)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 whitespace-nowrap bg-white sticky left-0 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] z-10 border-r border-gray-100">
                                <div class="font-bold text-gray-900">{{ $data['name'] }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $data['type'] }} (ID: {{ $data['employee_id'] }})</div>
                            </td>
                            @foreach($leaveTypes as $type)
                                @if($employeeType === 'all' || $type->applicable_to === 'both' || $type->applicable_to === $employeeType)
                                    <td class="py-3 px-4 whitespace-nowrap text-center border-l border-gray-100">
                                        @if(isset($data['balances'][$type->id]))
                                            @php 
                                                $bal = $data['balances'][$type->id]; 
                                                $isDanger = $bal['remaining'] == 0;
                                            @endphp
                                            <div class="font-medium text-gray-800">
                                                <span class="{{ $isDanger ? 'text-red-600' : 'text-emerald-600' }}">{{ $bal['enjoyed'] }}</span> 
                                                <span class="text-gray-400 mx-1">/</span> 
                                                <span>{{ $bal['total'] }}</span>
                                            </div>
                                            <div class="text-[10px] text-gray-500 mt-0.5">অবশিষ্ট: <span class="{{ $isDanger ? 'text-red-500 font-bold' : '' }}">{{ $bal['remaining'] }}</span></div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="py-8 text-center text-gray-500">কোনো ডাটা পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
