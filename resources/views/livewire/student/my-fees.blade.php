<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ফি ও পেমেন্ট</h2>
            <p class="text-sm text-gray-500">আপনার সমস্ত বকেয়া এবং পরিশোধিত ফি এর বিবরণ</p>
        </div>
        <div class="flex bg-white rounded-lg p-1 shadow-sm border border-gray-200">
            <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filterStatus === 'all' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">সকল</button>
            <button wire:click="$set('filterStatus', 0)" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filterStatus === 0 ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">বকেয়া</button>
            <button wire:click="$set('filterStatus', 1)" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filterStatus === 1 ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">আংশিক</button>
            <button wire:click="$set('filterStatus', 2)" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filterStatus === 2 ? 'bg-emerald-100 text-emerald-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">পরিশোধিত</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">মোট বিল</p>
                    <h3 class="text-2xl font-bold text-gray-800">৳{{ number_format($totalBilled, 2) }}</h3>
                </div>
                <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">মোট পরিশোধিত</p>
                    <h3 class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalPaid, 2) }}</h3>
                </div>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">মোট বকেয়া</p>
                    <h3 class="text-2xl font-bold text-red-600">৳{{ number_format($totalDue, 2) }}</h3>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-medium text-gray-900">ইনভয়েস সমূহ</h3>
        </div>
        
        @if($invoices->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ইনভয়েস নং</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিবরণ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শেষ তারিখ (Due Date)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">পরিমাণ</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">বকেয়া</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $invoice->invoice_no }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->feeType->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $invoice->month_year ? \Carbon\Carbon::parse($invoice->month_year)->format('F Y') : '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}
                                    @if($invoice->status->value !== 2 && \Carbon\Carbon::parse($invoice->due_date)->isPast())
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            মেয়াদোত্তীর্ণ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    ৳{{ number_format($invoice->net_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 text-right">
                                    ৳{{ number_format($invoice->remaining_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status->color() === 'emerald' ? 'bg-emerald-100 text-emerald-800' : ($invoice->status->color() === 'red' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800') }}">
                                        {{ $invoice->status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">কোনো ইনভয়েস নেই</h3>
                <p class="mt-1 text-sm text-gray-500">আপনার কোনো ফি বা পেমেন্ট সংক্রান্ত তথ্য পাওয়া যায়নি।</p>
            </div>
        @endif
    </div>
</div>
