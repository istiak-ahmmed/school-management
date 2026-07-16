<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">শিক্ষার্থী প্রমোশন</h2>
        <p class="text-sm text-gray-500">শিক্ষার্থীদের এক শ্রেণী থেকে অন্য শ্রেণীতে প্রমোট করুন</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Source Selection -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">বর্তমান শ্রেণী (Source)</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">একাডেমিক ইয়ার <span class="text-red-500">*</span></label>
                    <select wire:model.live="from_academic_year_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    @error('from_academic_year_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span class="text-red-500">*</span></label>
                    <select wire:model.live="from_class_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" {{ !$from_academic_year_id ? 'disabled' : '' }}>
                        <option value="">নির্বাচন করুন</option>
                        @foreach($fromClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('from_class_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শাখা <span class="text-red-500">*</span></label>
                    <select wire:model.live="from_section_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" {{ !$from_class_id ? 'disabled' : '' }}>
                        <option value="">নির্বাচন করুন</option>
                        @foreach($fromSections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('from_section_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                @if(count($exams) > 0)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">মেধা তালিকা অনুযায়ী রোল নির্ধারণ করতে পরীক্ষা নির্বাচন করুন (ঐচ্ছিক)</label>
                    <div class="space-y-2">
                        @foreach($exams as $exam)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="selected_exams" value="{{ $exam->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $exam->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">পরীক্ষা নির্বাচন না করলে শিক্ষার্থীদের বর্তমান রোল নম্বরটি ডিফল্ট হিসেবে থাকবে।</p>
                </div>
                @endif

                <button wire:click="fetchStudents" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition" {{ !$from_section_id ? 'disabled' : '' }}>
                    শিক্ষার্থী খুঁজুন
                </button>
            </div>
        </div>

        <!-- Target Selection -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">নতুন শ্রেণী (Target)</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">একাডেমিক ইয়ার <span class="text-red-500">*</span></label>
                    <select wire:model.live="to_academic_year_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    @error('to_academic_year_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span class="text-red-500">*</span></label>
                    <select wire:model.live="to_class_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" {{ !$to_academic_year_id ? 'disabled' : '' }}>
                        <option value="">নির্বাচন করুন</option>
                        @foreach($toClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('to_class_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">শাখা <span class="text-red-500">*</span></label>
                    <select wire:model.live="to_section_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" {{ !$to_class_id ? 'disabled' : '' }}>
                        <option value="">নির্বাচন করুন</option>
                        @foreach($toSections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('to_section_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    @if(count($students) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">শিক্ষার্থীর তালিকা ({{ count($students) }} জন)</h3>
            <button wire:click="promoteStudents" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-sm">
                <span wire:loading.remove wire:target="promoteStudents">নির্বাচিতদের প্রমোট করুন</span>
                <span wire:loading wire:target="promoteStudents">অপেক্ষা করুন...</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">প্রমোট?</th>
                        <th class="px-6 py-3">আইডি</th>
                        <th class="px-6 py-3">নাম</th>
                        <th class="px-6 py-3 text-center">পূর্বের রোল</th>
                        @if(!empty($selected_exams))
                        <th class="px-6 py-3 text-center">মোট প্রাপ্ত নম্বর</th>
                        @endif
                        <th class="px-6 py-3 text-center">নতুন রোল <span class="text-red-500">*</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" wire:model="promotions.{{ $student->id }}.promote" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $student->admission_no }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $student->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $student->roll_no }}
                            </td>
                            @if(!empty($selected_exams))
                            <td class="px-6 py-4 text-center font-bold text-indigo-600">
                                {{ $promotions[$student->id]['total_marks'] ?? 0 }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <input type="text" wire:model="promotions.{{ $student->id }}.roll_no" class="w-20 mx-auto block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
