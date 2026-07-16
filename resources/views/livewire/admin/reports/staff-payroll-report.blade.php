<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">স্টাফ ও পে-রোল রিপোর্ট</h2>
            <p class="text-sm text-gray-500">Staff & Payroll Report</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if(count($payments) > 0)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-cyan-600 text-white rounded-lg shadow-sm hover:bg-cyan-700 flex items-center gap-2 transition">
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Month/Year -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">বেতনের মাস <span class="text-red-500">*</span></label>
                <input type="month" wire:model.live="month_year" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            </div>

            <!-- Employee Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">কর্মচারীর ধরন (Role)</label>
                <select wire:model.live="employee_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">-- সকল ধরন --</option>
                    <option value="teacher">শিক্ষক (Teacher)</option>
                    <option value="staff">স্টাফ (Staff)</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                <select wire:model.live="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">-- সকল স্ট্যাটাস --</option>
                    <option value="paid">পরিশোধিত (Paid)</option>
                    <option value="unpaid">অপরিশোধিত (Unpaid)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
        @if(!$month_year)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p>অনুগ্রহ করে একটি মাস নির্বাচন করুন।</p>
            </div>
        @else
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মোট কর্মচারী (Total Staff)</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $summary['total_employees'] }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                        <span class="font-bold text-lg">৳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">মোট পরিশোধিত (Total Paid)</p>
                        <p class="text-2xl font-bold text-green-600">৳ {{ number_format($summary['total_paid_amount'], 2) }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                        <span class="font-bold text-lg">৳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">বকেয়া বেতন (Pending Pay)</p>
                        <p class="text-2xl font-bold text-red-600">৳ {{ number_format($summary['total_pending_amount'], 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">ভাউচার ও তারিখ</th>
                                <th class="px-6 py-3">কর্মচারীর নাম ও ধরন</th>
                                <th class="px-6 py-3 text-right">মূল বেতন</th>
                                <th class="px-6 py-3 text-right">ভাতা/কর্তন</th>
                                <th class="px-6 py-3 text-right">নিট বেতন</th>
                                <th class="px-6 py-3 text-center">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                                @php
                                    $isPaid = $payment->status === \App\Enums\SalaryStatus::Paid;
                                    $employeeType = $payment->employee_type === \App\Enums\EmployeeType::Teacher ? 'Teacher' : 'Staff';
                                    $name = optional(optional($payment->employee)->user)->name ?? optional($payment->employee)->name ?? 'Unknown';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">{{ $payment->voucher_no }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $isPaid && $payment->paid_at ? $payment->paid_at->format('d M, Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">{{ $name }}</div>
                                        <div class="text-xs text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $employeeType }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right text-gray-600">
                                        {{ number_format($payment->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="text-green-600 text-xs">+{{ number_format($payment->total_allowance, 2) }}</div>
                                        <div class="text-red-600 text-xs">-{{ number_format($payment->total_deduction + $payment->advance_deducted, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-gray-800">
                                        ৳ {{ number_format($payment->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        @if($isPaid)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        কোনো পে-রোল তথ্য পাওয়া যায়নি।
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
