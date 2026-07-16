<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">আমার ক্লাস</h2>
        <p class="text-sm text-gray-500">আপনার নিয়োজিত সকল শ্রেণী, শাখা ও বিষয়ের তালিকা</p>
    </div>

    @if($grouped->count() > 0)
        <div class="space-y-4">
            @foreach($grouped as $class)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Class Header -->
                    <button 
                        wire:click="toggleClass({{ $class['class_id'] }})"
                        class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-violet-50/50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center text-violet-700 font-bold text-lg shrink-0">
                                {{ mb_substr($class['class_name'], 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $class['class_name'] }}</h3>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    @foreach($class['subjects'] as $subject)
                                        <span class="text-xs px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full font-medium">{{ $subject }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform {{ $expandedClass === $class['class_id'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    @if($expandedClass === $class['class_id'])
                        @php
                            // Get all sections with students count for this class
                            $classSections = \App\Models\Section::withCount(['students' => function($q) {
                                $q->where('status', 1);
                            }])->where('class_id', $class['class_id'])->get();
                        @endphp
                        
                        <div class="border-t border-gray-100 bg-gray-50/30 px-6 py-4 space-y-3">
                            @forelse($classSections as $section)
                                <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                                    <button
                                        wire:click="toggleSection({{ $section->id }})"
                                        class="w-full flex items-center justify-between px-5 py-3 text-left hover:bg-violet-50/50 transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 font-bold text-sm">
                                                {{ $section->name }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-800">শাখা: {{ $section->name }}</span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{{ $section->students_count }} শিক্ষার্থী</span>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform {{ $expandedSection === $section->id ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    @if($expandedSection === $section->id)
                                        <div class="border-t border-gray-100">
                                            @if($students->count() > 0)
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-100">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">রোল</th>
                                                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">নাম</th>
                                                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">আইডি</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100">
                                                            @foreach($students as $student)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-5 py-2.5 text-sm text-gray-600 font-medium">{{ $student->roll_no }}</td>
                                                                    <td class="px-5 py-2.5 text-sm font-semibold text-gray-900">{{ $student->name }}</td>
                                                                    <td class="px-5 py-2.5 text-xs text-gray-500">{{ $student->admission_no }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="p-6 text-center text-gray-500 text-sm">এই শাখায় কোনো শিক্ষার্থী নেই।</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 py-2">এই শ্রেণীতে কোনো শাখা নেই।</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো ক্লাস নিয়োজিত নেই</h3>
            <p class="text-sm text-gray-500">আপনাকে এখনো কোনো ক্লাস বা বিষয়ে নিয়োগ দেওয়া হয়নি। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।</p>
        </div>
    @endif
</div>
