<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">মার্কস এন্ট্রি</h2>
        <p class="text-sm text-gray-500">শুধুমাত্র আপনার নিয়োজিত বিষয়ের মার্কস এন্ট্রি করা যাবে</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-sm font-medium">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Exam -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষা বাছাই করুন</label>
                <select wire:model.live="exam_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm">
                    <option value="">-- পরীক্ষা নির্বাচন করুন --</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী</label>
                <select wire:model.live="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm" {{ empty($exam_id) ? 'disabled' : '' }}>
                    <option value="">-- শ্রেণী নির্বাচন করুন --</option>
                    @foreach($allowedClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
                @if($exam_id && $allowedClasses->isEmpty())
                    <p class="text-xs text-orange-500 mt-1">এই পরীক্ষায় আপনার কোনো নিয়োজিত ক্লাস নেই।</p>
                @endif
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">বিষয়</label>
                <select wire:model.live="subject_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm" {{ empty($class_id) ? 'disabled' : '' }}>
                    <option value="">-- বিষয় নির্বাচন করুন --</option>
                    @foreach($allowedSubjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section (optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">শাখা (ঐচ্ছিক)</label>
                <select wire:model.live="section_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm" {{ empty($class_id) ? 'disabled' : '' }}>
                    <option value="">-- সব শাখা --</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($routine)
            <div class="mt-4 flex flex-wrap gap-4 pt-4 border-t border-gray-100 text-sm">
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="font-medium">পূর্ণমান:</span>
                    <span class="font-bold text-gray-900">{{ $routine->full_marks }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="font-medium">পাস মার্ক:</span>
                    <span class="font-bold text-gray-900">{{ $routine->pass_marks }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="font-medium">তারিখ:</span>
                    <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($routine->exam_date)->format('d M, Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="font-medium">সময়:</span>
                    <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}</span>
                </div>
            </div>
        @endif
    </div>

    @if($routine && count($students) > 0)
        <x-shared.marks-entry-table :students="$students" :marksData="$marksData" :routine="$routine" color="violet" />
    @elseif($exam_id && $class_id && $subject_id && !$routine)
        {{-- Error shown via session flash --}}
    @endif
</div>
