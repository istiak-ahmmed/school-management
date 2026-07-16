<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ক্লাস রুটিন</h2>
            <p class="text-sm text-gray-500">আপনার সাপ্তাহিক শিক্ষণ সময়সূচী</p>
        </div>
        <div class="flex bg-white rounded-lg p-1 shadow-sm border border-gray-200 overflow-x-auto w-full sm:w-auto gap-0.5">
            @foreach($days as $dayNum => $label)
                <button
                    wire:click="$set('selectedDay', {{ $dayNum }})"
                    class="px-3 py-2 text-sm font-medium rounded-md whitespace-nowrap transition-colors {{ $selectedDay === $dayNum ? 'bg-violet-100 text-violet-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-violet-50/50">
            <h3 class="text-lg font-bold text-violet-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $days[$selectedDay] ?? 'দিন' }}
            </h3>
        </div>

        @if(isset($routinesByDay[$selectedDay]) && $routinesByDay[$selectedDay]->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">সময়</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বিষয়</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">শ্রেণী</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">শাখা</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">রুম</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($routinesByDay[$selectedDay] as $routine)
                            <tr class="hover:bg-violet-50/20 transition {{ $routine->is_break ? 'bg-orange-50/20' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($routine->is_break)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">☕ বিরতি</span>
                                    @else
                                        <span class="text-sm font-bold text-violet-700">{{ $routine->subject->name ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                    {{ $routine->schoolClass->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $routine->section->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$routine->is_break)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            রুম: {{ $routine->room ?? '-' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">কোনো ক্লাস নেই</h3>
                <p class="mt-1 text-sm text-gray-500">এই দিনে আপনার কোনো ক্লাস নেই।</p>
            </div>
        @endif
    </div>
</div>
