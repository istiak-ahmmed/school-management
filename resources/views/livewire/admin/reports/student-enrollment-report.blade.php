<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">শিক্ষার্থী ভর্তি রিপোর্ট</h2>
            <p class="text-sm text-gray-500">Student Enrollment Report</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if(count($enrollments) > 0)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download CSV
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            ফিল্টার করুন (Filters)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Academic Year -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শিক্ষাবর্ষ <span class="text-red-500">*</span></label>
                <select wire:model.live="academic_year_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- শিক্ষাবর্ষ নির্বাচন করুন --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শ্রেণী (Class)</label>
                <select wire:model.live="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- সকল শ্রেণী --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শাখা (Section)</label>
                <select wire:model.live="section_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if(empty($sections)) disabled @endif>
                    <option value="">-- সকল শাখা --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">লিঙ্গ (Gender)</label>
                <select wire:model.live="gender" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- সকল --</option>
                    <option value="male">ছেলে (Male)</option>
                    <option value="female">মেয়ে (Female)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
        @if(!$academic_year_id)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p>অনুগ্রহ করে একটি শিক্ষাবর্ষ নির্বাচন করুন।</p>
            </div>
        @else
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মোট শিক্ষার্থী</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">ছেলে (Male)</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['male'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মেয়ে (Female)</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['female'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">Roll / ID</th>
                                <th class="px-6 py-3">শিক্ষার্থীর নাম</th>
                                <th class="px-6 py-3">শ্রেণী ও শাখা</th>
                                <th class="px-6 py-3">লিঙ্গ</th>
                                <th class="px-6 py-3">ফোন নম্বর</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($enrollments as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">{{ $student->roll_no }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->admission_no ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            @if(!empty($student->photo_path))
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($student->photo_path) }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                    {{ mb_substr($student->user->name ?? $student->name ?? 'U', 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-800">{{ $student->user->name ?? $student->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ $student->schoolClass->name ?? '' }} - {{ $student->section->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ ucfirst($student->gender->value ?? $student->gender ?? '-') }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $student->guardians->first()->phone ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        কোনো তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
