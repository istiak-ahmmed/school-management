<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">বেতন স্লিপ</h2>
        <p class="text-sm text-gray-500">আপনার মাসিক বেতনের বিবরণ</p>
    </div>

    @if($salaries->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6">
                <p class="text-sm font-medium text-gray-500 mb-1">মোট পরিশোধিত বেতন</p>
                <h3 class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalPaid, 2) }}</h3>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6">
                <p class="text-sm font-medium text-gray-500 mb-1">অপেক্ষমাণ বেতন মাস</p>
                <h3 class="text-2xl font-bold text-orange-600">{{ $pendingCount }}</h3>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm font-medium text-gray-500 mb-1">মোট রেকর্ড</p>
                <h3 class="text-2xl font-bold text-gray-700">{{ $salaries->count() }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-800">বেতনের তালিকা</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">মাস (Month)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">মূল বেতন</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">মোট ভাতা</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">মোট কর্তন</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">নিট বেতন</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">স্ট্যাটাস</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">স্লিপ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($salaries as $salary)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $salary->month_year ? \Carbon\Carbon::parse($salary->month_year)->format('F Y') : 'N/A' }}
                                    </div>
                                    @if($salary->paid_at)
                                        <div class="text-xs text-gray-500">পরিশোধ: {{ \Carbon\Carbon::parse($salary->paid_at)->format('d M, Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">৳{{ number_format($salary->basic_salary, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 text-right font-medium">+৳{{ number_format($salary->total_allowance, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right font-medium">-৳{{ number_format($salary->total_deduction, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-base font-bold text-gray-900">৳{{ number_format($salary->net_salary, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusVal = $salary->status->value ?? null;
                                        $statusLabel = $salary->status->label();
                                        $statusClass = $statusVal === 0 ? 'bg-emerald-100 text-emerald-800' : ($statusVal === 1 ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($salary->status->value === 0)
                                        <a href="{{ route('admin.finance.salary-slip', $salary->id) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 border border-violet-200 shadow-sm text-xs font-medium rounded-md text-violet-700 bg-violet-50 hover:bg-violet-100">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            প্রিন্ট
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো বেতনের রেকর্ড নেই</h3>
            <p class="text-sm text-gray-500">আপনার এখনো কোনো বেতন পরিশোধের রেকর্ড নেই।</p>
        </div>
    @endif
</div>
