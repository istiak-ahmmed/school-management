<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">পরীক্ষা ব্যবস্থাপনা</h2>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            নতুন পরীক্ষা
        </button>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm">
                        <th scope="col" class="px-5 py-3 font-medium text-center cursor-pointer hover:bg-gray-100 text-gray-500 text-xs tracking-wider"  wire:click="sortBy('id')" >
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
                        <th class="px-5 py-3 font-medium">পরীক্ষার নাম</th>
                        <th class="px-5 py-3 font-medium">কোড</th>
                        <th class="px-5 py-3 font-medium">ধরন</th>
                        <th class="px-5 py-3 font-medium">শিক্ষাবর্ষ</th>
                        <th class="px-5 py-3 font-medium">তারিখ (শুরু - শেষ)</th>
                        <th class="px-5 py-3 font-medium text-center">অবস্থা</th>
                        <th class="px-5 py-3 font-medium text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-800 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-5 py-3 text-gray-800 font-medium">
                                {{ $exam->name }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-sm">
                                {{ $exam->code ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-sm">
                                {{ $exam->exam_type->label() }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-sm">
                                {{ $exam->academicYear->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-sm">
                                {{ $exam->start_date ? $exam->start_date->format('d M, Y') : '-' }} <br>
                                {{ $exam->end_date ? $exam->end_date->format('d M, Y') : '-' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <button wire:click="openStatusModal({{ $exam->id }})" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $exam->status->color() }}-100 text-{{ $exam->status->color() }}-700 hover:bg-{{ $exam->status->color() }}-200 transition">
                                    {{ $exam->status->label() }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="#" class="text-emerald-600 hover:bg-emerald-50 p-1.5 rounded transition" title="রুটিন" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </a>
                                    <button wire:click="edit({{ $exam->id }})" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="delete({{ $exam->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-500 hover:bg-red-50 p-1.5 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">কোনো পরীক্ষা পাওয়া যায়নি</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exams->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $exams->links() }}
            </div>
        @endif
    </div>

    <!-- Exam CRUD Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 mx-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">{{ $isEditMode ? 'পরীক্ষা আপডেট করুন' : 'নতুন পরীক্ষা যোগ করুন' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষার নাম <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="যেমন: ১ম সাময়িক পরীক্ষা">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">কোড (ঐচ্ছিক)</label>
                            <input type="text" wire:model="code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="EXM-101">
                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ধরন <span class="text-red-500">*</span></label>
                            <select wire:model="exam_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">নির্বাচন করুন</option>
                                @foreach($examTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error('exam_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষাবর্ষ <span class="text-red-500">*</span></label>
                            <select wire:model="academic_year_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">নির্বাচন করুন</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শুরুর তারিখ</label>
                            <input type="date" wire:model="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শেষের তারিখ</label>
                            <input type="date" wire:model="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ফলাফল প্রকাশের সম্ভাব্য তারিখ</label>
                            <input type="date" wire:model="result_publish_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('result_publish_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">বাতিল</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                            {{ $isEditMode ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Status Update Modal -->
    @if ($showStatusModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 mx-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">অবস্থা পরিবর্তন</h3>
                    <button wire:click="closeStatusModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="updateStatus">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">নতুন অবস্থা নির্বাচন করুন</label>
                        <select wire:model="newStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @foreach($examStatuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('newStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeStatusModal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">বাতিল</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">আপডেট</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
