<div>
    @section('header', 'খরচের তালিকা')

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
            <div class="relative w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="ভাউচার, খাত বা নাম দিয়ে খুঁজুন..." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.finance.expenses.report') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition">
                    রিপোর্ট দেখুন
                </a>
                <a href="{{ route('admin.finance.expenses.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                    খরচ এন্ট্রি করুন
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th wire:click="sortBy('expense_date')" class="cursor-pointer px-5 py-3 font-medium hover:bg-gray-100">
                            তারিখ @if($sortField === 'expense_date') @if($sortDirection === 'asc') &uarr; @else &darr; @endif @endif
                        </th>
                        <th wire:click="sortBy('voucher_no')" class="cursor-pointer px-5 py-3 font-medium hover:bg-gray-100">
                            ভাউচার নং @if($sortField === 'voucher_no') @if($sortDirection === 'asc') &uarr; @else &darr; @endif @endif
                        </th>
                        <th class="px-5 py-3 font-medium">খাত</th>
                        <th class="px-5 py-3 font-medium">প্রাপক (Paid To)</th>
                        <th wire:click="sortBy('amount')" class="cursor-pointer px-5 py-3 font-medium text-right hover:bg-gray-100">
                            পরিমাণ (৳) @if($sortField === 'amount') @if($sortDirection === 'asc') &uarr; @else &darr; @endif @endif
                        </th>
                        <th class="px-5 py-3 font-medium">পেমেন্ট মেথড</th>
                        <th class="px-5 py-3 font-medium text-center">বিস্তারিত</th>
                        <th class="px-5 py-3 font-medium text-right">একশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($expenses as $expense)
                        <tr>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-900">{{ $expense->expense_date->format('d M, Y') }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500">{{ $expense->voucher_no }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-900 font-medium">{{ optional($expense->category)->name }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500">{{ $expense->paid_to }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-gray-900">{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                {{ optional($expense->paymentMethod)->bn_name ?? optional($expense->paymentMethod)->en_name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <button wire:click="viewExpense({{ $expense->id }})" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg inline-flex items-center text-xs font-semibold transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    ভিউ
                                </button>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-medium">
                                <a href="{{ route('admin.finance.expenses.edit', $expense->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">এডিট</a>
                                <button wire:click="delete({{ $expense->id }})" class="text-red-500 hover:text-red-700" onclick="confirm('আপনি কি নিশ্চিত যে এই খরচটি মুছে ফেলতে চান?') || event.stopImmediatePropagation()">ডিলিট</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-4 text-center text-gray-500">কোনো খরচ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Expense Details & Media Modal -->
    @if($showDetailsModal && $viewingExpense)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDetailsModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full">
                
                <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        খরচের বিস্তারিত
                    </h3>
                    <button type="button" wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-500 bg-white rounded-full p-1 shadow-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="bg-white p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Col: Details -->
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4 border-b pb-2">তথ্য (Information)</h4>
                            <dl class="space-y-4">
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">ভাউচার নং:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2 font-mono font-medium">{{ $viewingExpense->voucher_no }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">তারিখ:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $viewingExpense->expense_date->format('d M, Y') }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">খাত:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2 font-bold">{{ optional($viewingExpense->category)->name }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">পরিমাণ:</dt>
                                    <dd class="text-lg text-indigo-700 col-span-2 font-bold">৳ {{ number_format($viewingExpense->amount, 2) }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">পেমেন্ট মেথড:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        {{ optional($viewingExpense->paymentMethod)->bn_name ?? optional($viewingExpense->paymentMethod)->en_name ?? '-' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">প্রাপক:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $viewingExpense->paid_to ?: '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <dt class="text-sm font-medium text-gray-500">এন্ট্রি করেছেন:</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ optional($viewingExpense->enterer)->name ?: '-' }}</dd>
                                </div>
                                @if($viewingExpense->note)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">নোট/মন্তব্য:</dt>
                                    <dd class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $viewingExpense->note }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Right Col: Media Gallery -->
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4 border-b pb-2">অ্যাটাচড ফাইলসমূহ (Attachments)</h4>
                            
                            @if(count($viewingExpense->media) > 0)
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($viewingExpense->media as $media)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden group relative">
                                            @if(Str::startsWith($media->mime_type, 'image/'))
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="block">
                                                    <img src="{{ $media->getUrl('optimized') }}" alt="Attachment" class="w-full h-32 object-cover hover:opacity-80 transition">
                                                </a>
                                            @else
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="flex flex-col items-center justify-center h-32 bg-gray-50 hover:bg-gray-100 transition">
                                                    <svg class="w-10 h-10 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    <span class="text-xs font-semibold text-gray-600">PDF Document</span>
                                                </a>
                                            @endif
                                            
                                            <div class="absolute bottom-0 inset-x-0 bg-black bg-opacity-50 text-white text-[10px] py-1 px-2 truncate opacity-0 group-hover:opacity-100 transition">
                                                {{ $media->name }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-8 text-center border border-dashed border-gray-200">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-sm text-gray-500 font-medium">কোনো ফাইল যুক্ত করা হয়নি</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 border-t border-gray-100 px-6 py-4 flex justify-end">
                    <button type="button" wire:click="closeDetailsModal" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
