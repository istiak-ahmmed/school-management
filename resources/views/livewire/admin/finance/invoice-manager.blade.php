<div>
    @section('header', 'ইনভয়েস ব্যবস্থাপনা')

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">মোট প্রত্যাশিত</p>
            <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalExpected, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $currentMonth }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">মোট সংগৃহীত</p>
            <p class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalCollected, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $currentMonth }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">মোট বকেয়া</p>
            <p class="text-2xl font-bold text-red-500">৳{{ number_format($totalDue, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $currentMonth }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        {{-- Header: Generate Button + Filters --}}
        <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Month Filter --}}
                <input type="month" wire:model.live="monthFilter"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"/>

                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">সকল অবস্থা</option>
                    @foreach ($statusOptions as $s)
                        <option value="{{ $s->value }}">{{ $s->label() }}</option>
                    @endforeach
                </select>

                {{-- Search --}}
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="নাম বা ইনভয়েস নম্বর..."
                        class="border border-gray-200 rounded-lg px-3 py-2 pl-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                </div>
            </div>

            {{-- Generate Button --}}
            <button
                wire:click="generateMonthlyInvoices"
                wire:loading.attr="disabled"
                wire:target="generateMonthlyInvoices"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="generateMonthlyInvoices">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span wire:loading wire:target="generateMonthlyInvoices">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </span>
                এই মাসের ইনভয়েস তৈরি করুন
            </button>

            <button
                wire:click="openCustomModal"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                নতুন ইনভয়েস তৈরি করুন (Custom)
            </button>
        </div>

        {{-- Generate Result Message --}}
        @if ($generateMessage)
            <div class="mx-5 mt-4 px-4 py-3 rounded-lg text-sm font-medium
                {{ $generateSuccess ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' }}">
                {{ $generateMessage }}
            </div>
        @endif

        {{-- Invoice Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 font-medium">ইনভয়েস নং</th>
                        <th class="px-5 py-3 font-medium">শিক্ষার্থী</th>
                        <th class="px-5 py-3 font-medium">ফি ধরন</th>
                        <th class="px-5 py-3 font-medium">মাস</th>
                        <th class="px-5 py-3 font-medium text-right">পরিমাণ</th>
                        <th class="px-5 py-3 font-medium text-right">সংগৃহীত</th>
                        <th class="px-5 py-3 font-medium">বকেয়া তারিখ</th>
                        <th class="px-5 py-3 font-medium">অবস্থা</th>
                        <th class="px-5 py-3 font-medium text-center">একশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($invoices as $invoice)
                        @php
                            $colors = match($invoice->status->value) {
                                0 => 'bg-red-100 text-red-700',
                                1 => 'bg-yellow-100 text-yellow-700',
                                2 => 'bg-green-100 text-green-700',
                                3 => 'bg-blue-100 text-blue-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $invoice->invoice_no }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $invoice->student->user->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $invoice->feeType->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $invoice->month_year ?? '—' }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800">৳{{ number_format($invoice->net_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right font-medium {{ $invoice->payments_sum_amount_paid > 0 ? 'text-emerald-600' : 'text-gray-400' }}">
                                ৳{{ number_format($invoice->payments_sum_amount_paid ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $invoice->due_date?->format('d M Y') ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors }}">
                                    {{ $invoice->status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.finance.fee-collection') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium transition" title="সংগ্রহ করুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.finance.invoice.print', $invoice->id) }}" target="_blank" class="text-gray-500 hover:text-gray-700 transition" title="প্রিন্ট করুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">কোনো ইনভয়েস পাওয়া যায়নি</p>
                                    <p class="text-xs text-gray-400 mt-1">অনুসন্ধানের মানদণ্ড পরিবর্তন করুন অথবা নতুন ইনভয়েস তৈরি করুন।</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($invoices->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    {{-- Custom Invoice Modal --}}
    @if ($showCustomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6 mx-4">
                <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-bold text-gray-800">কাস্টম ইনভয়েস তৈরি করুন</h3>
                    <button wire:click="closeCustomModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveCustomInvoice">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ফি এর ধরন (Fee Type) <span class="text-red-500">*</span></label>
                            <select wire:model="customFeeTypeId" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">নির্বাচন করুন...</option>
                                @foreach($feeTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                            @error('customFeeTypeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">যাদের জন্য ইনভয়েস তৈরি হবে (ক্লাস নির্বাচন করুন) <span class="text-red-500">*</span></label>
                            <div class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 grid grid-cols-2 gap-2">
                                @foreach($schoolClasses as $cls)
                                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2 rounded border border-gray-100 shadow-sm hover:border-indigo-300 transition">
                                        <input type="checkbox" wire:model="customClassIds" value="{{ $cls->id }}" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">{{ $cls->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('customClassIds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">আপনি একসাথে একাধিক ক্লাস নির্বাচন করতে পারবেন।</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">বকেয়া তারিখ (Due Date) <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="customDueDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('customDueDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex gap-3 text-sm text-blue-800">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p><strong>বিঃদ্রঃ</strong> ফি এর পরিমাণ (Amount) ফি স্ট্রাকচার থেকে স্বয়ংক্রিয়ভাবে নেওয়া হবে। যদি কোনো শিক্ষার্থীর আগে থেকেই এই ফি-এর ইনভয়েস থাকে, তবে তাকে স্কিপ করা হবে।</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeCustomModal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">বাতিল</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <span wire:loading.remove wire:target="saveCustomInvoice">ইনভয়েস তৈরি করুন</span>
                            <span wire:loading wire:target="saveCustomInvoice">তৈরি হচ্ছে...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
