<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ফি সংগ্রহ রিপোর্ট</h2>
            <p class="text-sm text-gray-500">Fee Collection Report</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if(count($payments) > 0)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-sm hover:bg-green-700 flex items-center gap-2 transition">
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
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Date From -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ হতে <span class="text-red-500">*</span></label>
                <input type="date" wire:model.live="date_from" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>

            <!-- Date To -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ পর্যন্ত <span class="text-red-500">*</span></label>
                <input type="date" wire:model.live="date_to" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>

            <!-- Fee Type -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">ফি এর ধরন (Fee Type)</label>
                <select wire:model.live="fee_type_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">-- সকল ফি --</option>
                    @foreach($feeTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Method -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট মেথড</label>
                <select wire:model.live="payment_method_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">-- সকল মেথড --</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->bn_name ?? $method->en_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">শ্রেণী (Class)</label>
                <select wire:model.live="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">-- সকল শ্রেণী --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div class="col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">শাখা (Section)</label>
                <select wire:model.live="section_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" @if(empty($sections)) disabled @endif>
                    <option value="">-- সকল শাখা --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                    <span class="font-bold text-lg">৳</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">মোট সংগ্রহ</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($summary['total'], 2) }}</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">মোট পেমেন্ট সংখ্যা</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $summary['count'] }}</p>
                </div>
            </div>

            <!-- Method Breakdown -->
            <div class="lg:col-span-2 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 overflow-x-auto">
                @forelse($summary['methods'] as $method)
                <div class="px-4 border-r border-gray-200 last:border-0 shrink-0">
                    <p class="text-xs text-gray-500 font-medium">{{ $method['name'] }}</p>
                    <p class="text-lg font-bold text-gray-800">৳ {{ number_format($method['amount'], 2) }}</p>
                </div>
                @empty
                <div class="text-sm text-gray-500">কোনো পেমেন্ট মেথড পাওয়া যায়নি।</div>
                @endforelse
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">তারিখ ও রসিদ</th>
                            <th class="px-6 py-3">শিক্ষার্থী</th>
                            <th class="px-6 py-3">শ্রেণী ও শাখা</th>
                            <th class="px-6 py-3">ফি এর ধরন</th>
                            <th class="px-6 py-3">পেমেন্ট মেথড</th>
                            <th class="px-6 py-3 text-right">পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-800">{{ optional($payment->paid_at)->format('d M, Y') ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->payment_no }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-800">{{ optional(optional($payment->invoice)->student)->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ optional(optional($payment->invoice)->student)->admission_no ?? '' }}</div>
                                </td>
                                @php
                                    $student = optional($payment->invoice)->student;
                                @endphp
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $student ? optional($student->schoolClass)->name . ' - ' . optional($student->section)->name : '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        {{ optional(optional($payment->invoice)->feeType)->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-600">
                                    {{ optional($payment->paymentMethod)->bn_name ?? optional($payment->paymentMethod)->en_name ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right font-bold text-gray-800">
                                    ৳ {{ number_format($payment->amount_paid, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    নির্বাচিত শর্ত অনুযায়ী কোনো ফি সংগ্রহের তথ্য পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
