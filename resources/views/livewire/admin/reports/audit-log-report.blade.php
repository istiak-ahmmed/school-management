<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">সিস্টেম অডিট লগ</h2>
            <p class="text-sm text-gray-500">System Audit Log Viewer</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">অনুসন্ধান (Search)</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="ব্যবহারকারী, মডেল বা ডেসক্রিপশন খুঁজুন..." class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ইভেন্ট (Event)</label>
                <select wire:model.live="event" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">সকল ইভেন্ট</option>
                    <option value="created">Created (তৈরি)</option>
                    <option value="updated">Updated (আপডেট)</option>
                    <option value="deleted">Deleted (ডিলিট)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">লগ নাম (Log Name)</label>
                <select wire:model.live="log_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">সকল লগ</option>
                    @foreach($logNames as $name)
                        <option value="{{ $name }}">{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative">
        <div wire:loading.class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center">
            <div wire:loading class="animate-spin rounded-full h-8 w-8 border-b-2 border-slate-600"></div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">সময় (Time)</th>
                        <th class="px-6 py-4">ব্যবহারকারী (User)</th>
                        <th class="px-6 py-4">ইভেন্ট (Event)</th>
                        <th class="px-6 py-4">বিষয় (Subject)</th>
                        <th class="px-6 py-4">বিস্তারিত (Description)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-500">
                                {{ $log->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->causer)
                                    <div class="font-medium text-gray-800">{{ $log->causer->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ class_basename($log->causer_type) }} #{{ $log->causer_id }}</div>
                                @else
                                    <span class="text-gray-400 italic">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $eventColor = match($log->event) {
                                        'created' => 'bg-green-100 text-green-800',
                                        'updated' => 'bg-blue-100 text-blue-800',
                                        'deleted' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eventColor }}">
                                    {{ ucfirst($log->event) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->subject_type)
                                    <div class="text-sm font-medium text-gray-700">{{ class_basename($log->subject_type) }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $log->subject_id }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-600">{{ $log->description }}</span>
                                @if($log->properties && count($log->properties) > 0)
                                    <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded border border-gray-100 max-w-xs overflow-x-auto" x-data="{ expanded: false }">
                                        <button @click="expanded = !expanded" class="text-slate-600 hover:text-slate-800 font-medium mb-1">
                                            <span x-show="!expanded">View Details ▼</span>
                                            <span x-show="expanded">Hide Details ▲</span>
                                        </button>
                                        <div x-show="expanded" x-transition>
                                            <pre class="whitespace-pre-wrap">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                কোনো লগ তথ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
