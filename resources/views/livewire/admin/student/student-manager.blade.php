@section('header', 'শিক্ষার্থী ব্যবস্থাপনা')

<div class="space-y-6 print:m-0 print:p-0">
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">শিক্ষার্থী তালিকা</h2>
            <p class="text-sm text-gray-500">সকল শিক্ষার্থীর তথ্য দেখুন ও পরিচালনা করুন</p>
        </div>
        <a href="{{ route('admin.students.admission') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            নতুন শিক্ষার্থী যোগ করুন
        </a>
    </div>

    {{-- ── Filters & Actions ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 print:hidden">
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.400ms="search"
                       type="search" id="student-search"
                       placeholder="নাম বা ভর্তি নম্বর দিয়ে খুঁজুন..."
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-200">
            </div>

            {{-- Class filter --}}
            <select wire:model.live="filterClass" id="filter-class"
                    class="rounded-xl border-gray-200 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-200">
                <option value="">-- সকল শ্রেণী --</option>
                @foreach($this->classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>

            {{-- Section filter --}}
            <select wire:model.live="filterSection" id="filter-section"
                    class="rounded-xl border-gray-200 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-200"
                    @disabled(!$filterClass)>
                <option value="">-- সকল শাখা --</option>
                @foreach($this->sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select wire:model.live="filterStatus" id="filter-status"
                    class="rounded-xl border-gray-200 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-200">
                <option value="">-- সকল স্ট্যাটাস --</option>
                <option value="1">সক্রিয়</option>
                <option value="0">নিষ্ক্রিয়</option>
                <option value="2">পাশ</option>
                <option value="3">বহিষ্কৃত</option>
            </select>

            {{-- Export --}}
            <x-export-button />
        </div>
    </div>

    {{-- Bulk Actions (shown when items are selected) --}}
    @if(count($selectedIds) > 0)
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-3 flex items-center justify-between">
            <span class="text-sm text-indigo-700 font-medium">{{ count($selectedIds) }}টি শিক্ষার্থী নির্বাচিত</span>
            <div class="flex gap-2">
                <button wire:click="bulkDeactivate"
                        wire:confirm="{{ count($selectedIds) }}জন শিক্ষার্থীকে নিষ্ক্রিয় করবেন?"
                        class="px-3 py-1.5 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    নিষ্ক্রিয় করুন
                </button>
            </div>
        </div>
    @endif

    {{-- ── Data Table ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none">
        
        <!-- Printable Header -->
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">দারুল হিকমাহ আল-ইসলামিয়া মাদ্রাসা</h1>
            <p class="text-lg text-gray-600 mt-1">শিক্ষার্থী তালিকা</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="bg-gray-50 text-center cursor-pointer hover:bg-gray-100 font-medium text-gray-500 text-xs tracking-wider"  wire:click="sortBy('id')" >
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
                        <th class="px-4 py-3.5 text-left w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-200">
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ছবি</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">নাম / ভর্তি নম্বর</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">শ্রেণী</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">শাখা</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($this->students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-center">
                                {{ $this->students->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ $student->id }}"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-200">
                            </td>
                            <td class="px-4 py-3">
                                @if($student->photo_path)
                                    <img src="{{ asset('storage/' . $student->photo_path) }}"
                                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-100"
                                         alt="{{ $student->user?->name }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ mb_substr($student->user?->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $student->user?->name ?? '—' }}</div>
                                <div class="text-xs font-mono text-indigo-500">{{ $student->admission_no }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $student->schoolClass?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $student->section?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status_color }}">
                                    {{ $student->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.students.profile', $student->id) }}"
                                       class="text-xs px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                                        প্রোফাইল
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student->id) }}"
                                       class="text-xs px-2.5 py-1 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition">
                                        এডিট
                                    </a>
                                    @if($student->status === 1)
                                        <button wire:click="deactivate({{ $student->id }})"
                                                wire:confirm="এই শিক্ষার্থীকে নিষ্ক্রিয় করবেন?"
                                                class="text-xs px-2.5 py-1 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                                            নিষ্ক্রিয়
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                <div class="text-4xl mb-3">🎒</div>
                                <p class="text-sm">কোনো শিক্ষার্থী পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->students->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 print:hidden">
                {{ $this->students->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

</div>
