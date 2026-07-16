<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">বকেয়া ফি রিপোর্ট</h2>
            <p class="text-sm text-gray-500">Fee Defaulters Report & SMS</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if(count($defaulters) > 0)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 flex items-center gap-2 transition">
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
                <select wire:model.live="academic_year_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">-- শিক্ষাবর্ষ নির্বাচন করুন --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শ্রেণী (Class)</label>
                <select wire:model.live="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">-- সকল শ্রেণী --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">শাখা (Section)</label>
                <select wire:model.live="section_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" @if(empty($sections)) disabled @endif>
                    <option value="">-- সকল শাখা --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Overdue Days -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">কত দিন বকেয়া?</label>
                <select wire:model.live="overdue_days" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">-- সকল বকেয়া --</option>
                    <option value="7">৭ দিনের বেশি (7+ Days)</option>
                    <option value="15">১৫ দিনের বেশি (15+ Days)</option>
                    <option value="30">১ মাসের বেশি (30+ Days)</option>
                    <option value="60">২ মাসের বেশি (60+ Days)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

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
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                        <span class="font-bold text-lg">৳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মোট বকেয়া পরিমাণ</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($summary['total_due'], 2) }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">বকেয়া ইনভয়েস</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['total_invoices'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">বকেয়াদার শিক্ষার্থী</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['total_students'] }}</p>
                    </div>
                </div>
            </div>

            <!-- SMS Integration Box -->
            @if(count($defaulters) > 0)
            <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100 mb-6">
                <h3 class="text-lg font-bold text-indigo-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    বকেয়াদারদের SMS পাঠান
                </h3>
                <p class="text-sm text-indigo-700 mb-4">নিচের তালিকা থেকে শিক্ষার্থী নির্বাচন করে একসাথে বকেয়া পরিশোধের SMS পাঠাতে পারেন। বর্তমানে <span class="font-bold text-indigo-900">{{ count($selectedInvoices) }}</span> জনকে নির্বাচন করা হয়েছে।</p>
                
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    <div class="flex-grow w-full">
                        <textarea wire:model="smsMessage" rows="3" class="w-full rounded-lg border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="SMS মেসেজ লিখুন..."></textarea>
                        @error('smsMessage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-indigo-600 mt-1">ক্যারেক্টার কাউন্ট: {{ mb_strlen($smsMessage) }} (সর্বোচ্চ ১৬০)</p>
                    </div>
                    <button wire:click="sendBulkSms" wire:loading.attr="disabled" class="shrink-0 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:bg-indigo-700 flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendBulkSms">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </span>
                        <span wire:loading wire:target="sendBulkSms">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                        <span>SMS পাঠান</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 w-10">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-3">ইনভয়েস নং</th>
                                <th class="px-6 py-3">শিক্ষার্থী</th>
                                <th class="px-6 py-3">শ্রেণী ও শাখা</th>
                                <th class="px-6 py-3">ফি এর ধরন</th>
                                <th class="px-6 py-3">শেষ তারিখ ও বকেয়া দিন</th>
                                <th class="px-6 py-3 text-right">বকেয়া পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($defaulters as $invoice)
                                <tr class="hover:bg-gray-50 {{ in_array($invoice->id, $selectedInvoices) ? 'bg-indigo-50/30' : '' }}">
                                    <td class="px-6 py-3">
                                        <input type="checkbox" wire:model.live="selectedInvoices" value="{{ $invoice->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-3 font-medium text-gray-800">
                                        {{ $invoice->invoice_no }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">{{ optional($invoice->student)->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">
                                            📞 {{ optional($invoice->student)->phone ?? 'No Phone' }}
                                        </div>
                                    </td>
                                    @php
                                        $enrollment = optional($invoice->student)->currentEnrollment;
                                    @endphp
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $enrollment ? optional($enrollment->schoolClass)->name . ' - ' . optional($enrollment->section)->name : '-' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        {{ optional($invoice->feeType)->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-gray-800">{{ $invoice->due_date->format('d M, Y') }}</div>
                                        <div class="text-xs font-bold text-red-500">
                                            {{ \Carbon\Carbon::parse($invoice->due_date)->diffInDays(\Carbon\Carbon::today()) }} দিন পার হয়েছে
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-red-600">
                                        ৳ {{ number_format($invoice->actual_due, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        কোনো বকেয়া তথ্য পাওয়া যায়নি।
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
