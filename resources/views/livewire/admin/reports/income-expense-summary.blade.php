<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">আয়-ব্যয় সারসংক্ষেপ</h2>
            <p class="text-sm text-gray-500">Income vs Expense Summary</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ফিরে যান
            </a>
            @if(count($data['timeline']) > 0)
            <button wire:click="downloadCsv" class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow-sm hover:bg-purple-700 flex items-center gap-2 transition">
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
            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ হতে <span class="text-red-500">*</span></label>
                <input type="date" wire:model.live="date_from" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ পর্যন্ত <span class="text-red-500">*</span></label>
                <input type="date" wire:model.live="date_to" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- View Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">রিপোর্টের ধরন</label>
                <select wire:model.live="view_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="monthly">মাসিক (Monthly)</option>
                    <option value="daily">দৈনিক (Daily)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Income -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">মোট আয় (Total Income)</p>
                    <p class="text-2xl font-bold text-green-600">৳ {{ number_format($data['total_income'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>

            <!-- Total Expense -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">মোট ব্যয় (Total Expense)</p>
                    <p class="text-2xl font-bold text-red-600">৳ {{ number_format($data['total_expense'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>

            <!-- Net Profit/Loss -->
            @php $isProfit = $data['net_profit'] >= 0; @endphp
            <div class="bg-white p-6 rounded-xl shadow-sm border border-{{ $isProfit ? 'blue' : 'orange' }}-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">নিট লাভ/ক্ষতি (Net {{ $isProfit ? 'Profit' : 'Loss' }})</p>
                    <p class="text-2xl font-bold text-{{ $isProfit ? 'blue' : 'orange' }}-600">
                        {{ $isProfit ? '+' : '' }}৳ {{ number_format($data['net_profit'], 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-{{ $isProfit ? 'blue' : 'orange' }}-50 text-{{ $isProfit ? 'blue' : 'orange' }}-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Timeline Table -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">বিস্তারিত আয়-ব্যয়</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">সময়কাল (Period)</th>
                                <th class="px-6 py-3 text-right">আয় (Income)</th>
                                <th class="px-6 py-3 text-right">ব্যয় (Expense)</th>
                                <th class="px-6 py-3 text-right">লাভ/ক্ষতি</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data['timeline'] as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">
                                        {{ $row['display'] }}
                                    </td>
                                    <td class="px-6 py-3 text-right text-green-600 font-medium">
                                        {{ number_format($row['income'], 2) }}
                                    </td>
                                    <td class="px-6 py-3 text-right text-red-600 font-medium">
                                        {{ number_format($row['expense'], 2) }}
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold {{ $row['profit'] >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                                        {{ number_format($row['profit'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        কোনো তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Breakdown -->
            <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">ব্যয়ের খাত (Expense Breakdown)</h3>
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    @forelse($data['expense_categories'] as $cat)
                        <div class="mb-4 last:mb-0">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">{{ $cat['name'] }}</span>
                                <span class="text-gray-900 font-bold">৳ {{ number_format($cat['amount'], 2) }}</span>
                            </div>
                            @php
                                $percent = $data['total_expense'] > 0 ? ($cat['amount'] / $data['total_expense']) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="flex-grow flex items-center justify-center text-gray-500 text-sm">
                            কোনো ব্যয়ের খাত পাওয়া যায়নি।
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
