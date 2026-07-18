<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">আমার হাজিরা</h2>
            <p class="text-sm text-gray-500">উপস্থিতি এবং অনুপস্থিতির রিপোর্ট</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="selectedMonth" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="selectedYear" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-xs font-medium text-gray-500 mb-1">মোট ক্লাস</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalDays }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-4 text-center">
            <p class="text-xs font-medium text-emerald-600 mb-1">উপস্থিত</p>
            <h3 class="text-2xl font-bold text-emerald-700">{{ $presentDays }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-4 text-center">
            <p class="text-xs font-medium text-red-600 mb-1">অনুপস্থিত</p>
            <h3 class="text-2xl font-bold text-red-700">{{ $absentDays }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-4 text-center">
            <p class="text-xs font-medium text-orange-600 mb-1">বিলম্ব</p>
            <h3 class="text-2xl font-bold text-orange-700">{{ $lateDays }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center col-span-2 md:col-span-1 bg-emerald-50/50">
            <p class="text-xs font-medium text-gray-500 mb-1">উপস্থিতির হার</p>
            <h3 class="text-2xl font-bold text-emerald-600">{{ $attendancePercentage }}%</h3>
        </div>
    </div>

    <!-- Calendar View / Table View -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                {{ date('F Y', strtotime($selectedYear . '-' . $selectedMonth . '-01')) }} এর হাজিরা বিবরণী
            </h3>
        </div>
        
        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বার</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $record)
                            @php
                                $date = \Carbon\Carbon::parse($record->date);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $date->format('l') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($record->status === 1)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            উপস্থিত (Present)
                                        </span>
                                    @elseif($record->status === 2)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            অনুপস্থিত (Absent)
                                        </span>
                                    @elseif($record->status === 3)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            বিলম্ব (Late)
                                        </span>
                                    @elseif($record->status === 4)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            ছুটি (Excused)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->remarks ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">কোনো রেকর্ড নেই</h3>
                <p class="mt-1 text-sm text-gray-500">নির্বাচিত মাসের কোনো হাজিরার রেকর্ড পাওয়া যায়নি।</p>
            </div>
        @endif
    </div>
</div>
