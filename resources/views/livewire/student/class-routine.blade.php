<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ক্লাস রুটিন</h2>
            <p class="text-sm text-gray-500">আপনার সাপ্তাহিক ক্লাসের সময়সূচী</p>
        </div>
        <div class="flex bg-white rounded-lg p-1 shadow-sm border border-gray-200 overflow-x-auto w-full sm:w-auto">
            @foreach($days as $key => $label)
                <button 
                    wire:click="$set('selectedDay', '{{ $key }}')"
                    class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap transition-colors {{ $selectedDay === $key ? 'bg-emerald-100 text-emerald-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-emerald-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $days[$selectedDay] ?? 'দিন' }}
            </h3>
        </div>

        <div class="p-0">
            @if(isset($routinesByDay[$selectedDay]) && $routinesByDay[$selectedDay]->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সময়</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিষয়</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শিক্ষক</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রুম</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($routinesByDay[$selectedDay] as $routine)
                                <tr class="hover:bg-emerald-50/30 transition {{ $routine->is_break ? 'bg-orange-50/30' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($routine->is_break)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                ☕ বিরতি (Break)
                                            </span>
                                        @else
                                            <span class="text-sm font-bold text-emerald-700">
                                                {{ $routine->subject->name ?? 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$routine->is_break)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs">
                                                    {{ mb_substr($routine->teacher->user->name ?? '?', 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $routine->teacher->user->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$routine->is_break)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                রুম: {{ $routine->room ?? '-' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
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
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">আজ ছুটি</h3>
                    <p class="mt-1 text-sm text-gray-500">এই দিনে কোনো ক্লাসের সময়সূচী নেই।</p>
                </div>
            @endif
        </div>
    </div>
</div>
