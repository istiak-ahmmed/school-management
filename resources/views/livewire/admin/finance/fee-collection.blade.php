<div>
    @section('header', 'বেতন সংগ্রহ')

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ────────────────────────────────────────────────────────────────
             LEFT PANEL: Search & Student Info
        ──────────────────────────────────────────────────────────────── --}}
        <div class="xl:col-span-1 space-y-4">

            {{-- Step 1: Student Search --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-base font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">১</span>
                    শিক্ষার্থী অনুসন্ধান
                </h2>

                @if (!$selectedStudent)
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="নাম বা ভর্তি নম্বর লিখুন..."
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 pr-10"
                            autocomplete="off"
                        />
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>

                        {{-- Search Results Dropdown --}}
                        @if (!empty($searchResults))
                            <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                @foreach ($searchResults as $result)
                                    <button
                                        wire:click="selectStudent({{ $result['id'] }})"
                                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-indigo-50 text-left text-sm transition border-b border-gray-50 last:border-0"
                                    >
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs shrink-0">
                                            {{ mb_substr($result['user']['name'] ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $result['user']['name'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-400">ভর্তি নং: {{ $result['admission_no'] ?? '—' }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if(strlen($search) >= 1 && strlen($search) < 2)
                        <p class="text-xs text-gray-400 mt-1">অনুসন্ধানের জন্য কমপক্ষে ২ অক্ষর লিখুন</p>
                    @endif
                @else
                    {{-- Selected Student Card --}}
                    <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">
                            {{ mb_substr($selectedStudent->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $selectedStudent->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">ভর্তি নং: {{ $selectedStudent->admission_no ?? '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $selectedStudent->schoolClass->name ?? '—' }} | {{ $selectedStudent->section->name ?? '—' }}</p>
                        </div>
                        <button wire:click="clearStudent" class="text-gray-400 hover:text-red-500 transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Payment Methods Legend --}}
            @if ($selectedStudent)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-600 mb-3">পেমেন্ট পদ্ধতি</h3>
                <div class="grid grid-cols-3 gap-2 text-xs text-center">
                    @foreach($paymentMethods as $method)
                    <label class="cursor-pointer">
                        <input type="radio" wire:model="paymentMethod" value="{{ $method->value }}" class="sr-only peer">
                        <div class="p-2 border-2 border-gray-200 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 text-gray-600 font-medium transition">
                            {{ $method->label() }}
                        </div>
                    </label>
                    @endforeach
                </div>

                @if (in_array($paymentMethod, [1,2,3,5,6]))
                    <div class="mt-3">
                        <input type="text" wire:model="transactionId" placeholder="লেনদেন আইডি / চেক নম্বর"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
                    </div>
                @endif

                <div class="mt-3">
                    <input type="text" wire:model="paidByName" placeholder="পরিশোধকারীর নাম (ঐচ্ছিক)"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
                </div>
                <div class="mt-2">
                    <textarea wire:model="paymentNote" rows="2" placeholder="মন্তব্য (ঐচ্ছিক)"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                </div>
            </div>
            @endif
        </div>

        {{-- ────────────────────────────────────────────────────────────────
             RIGHT PANEL: Invoices + Payment
        ──────────────────────────────────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-4">

            @if (!$selectedStudent)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm">বাম পাশের অনুসন্ধান বাক্স থেকে একজন শিক্ষার্থী নির্বাচন করুন</p>
                </div>
            @else
                {{-- Step 2-3: Pending Invoices --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-base font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">২</span>
                        বকেয়া ইনভয়েস
                        <span class="ml-auto text-xs text-gray-400 font-normal">{{ count($pendingInvoices) }}টি পাওয়া গেছে</span>
                    </h2>

                    @if (empty($pendingInvoices))
                        <div class="text-center py-8 text-gray-400 text-sm">
                            <svg class="w-10 h-10 mx-auto mb-2 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                            এই শিক্ষার্থীর কোনো বকেয়া ইনভয়েস নেই
                        </div>
                    @else
                        <div class="space-y-2 max-h-72 overflow-y-auto">
                            @foreach ($pendingInvoices as $invoice)
                                @php
                                    $isSelected = in_array($invoice['id'], $selectedInvoiceIds);
                                @endphp
                                <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition
                                    {{ $isSelected ? 'bg-indigo-50 border-indigo-400' : 'bg-gray-50 border-gray-200 hover:border-indigo-300' }}">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedInvoiceIds"
                                        value="{{ $invoice['id'] }}"
                                        class="mt-0.5 w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="font-medium text-gray-800 text-sm">{{ $invoice['fee_type']['name'] ?? 'N/A' }}</span>
                                            <span class="font-bold text-indigo-700 text-sm">৳{{ number_format($invoice['net_amount'], 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs text-gray-400">{{ $invoice['invoice_no'] }}</span>
                                            @if ($invoice['month_year'])
                                                <span class="text-xs text-gray-400">| {{ $invoice['month_year'] }}</span>
                                            @endif
                                            @if ($invoice['due_date'])
                                                <span class="text-xs text-gray-400">| বকেয়া: {{ \Carbon\Carbon::parse($invoice['due_date'])->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0
                                        {{ $invoice['status'] === 0 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $invoice['status'] === 0 ? 'অপরিশোধিত' : 'আংশিক' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Step 4-5: Amount & Collection --}}
                @if (!empty($selectedInvoiceIds))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h2 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">৩</span>
                            পরিশোধ সম্পাদন
                        </h2>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-4">
                            <span class="text-sm text-gray-600">নির্বাচিত মোট বকেয়া:</span>
                            <span class="text-lg font-bold text-gray-800">৳{{ number_format($totalDueAmount, 2) }}</span>
                        </div>

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p>• {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-medium text-gray-600 mb-1">পরিশোধিত পরিমাণ (৳) <span class="text-red-500">*</span></label>
                                <input
                                    type="number"
                                    wire:model.live="amountReceived"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    max="{{ $totalDueAmount }}"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 text-right font-bold text-lg text-gray-800"
                                />
                            </div>
                            
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-medium text-gray-600 mb-1">ছাড় / মওকুফ (৳)</label>
                                <input
                                    type="number"
                                    wire:model.live="discountAmount"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 text-right font-bold text-lg text-emerald-600"
                                />
                            </div>

                            <button
                                wire:click="collectPayment"
                                wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2 disabled:opacity-60"
                            >
                                <span wire:loading.remove wire:target="collectPayment">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span wire:loading wire:target="collectPayment">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                </span>
                                সংগ্রহ করুন
                            </button>
                        </div>
                        
                        @php
                            $totalPaid = (float)($amountReceived ?: 0) + (float)($discountAmount ?: 0);
                        @endphp
                        @if ($totalPaid > 0 && $totalPaid < $totalDueAmount)
                            <p class="text-xs text-yellow-600 mt-3 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                আংশিক পেমেন্ট — বাকি থাকবে ৳{{ number_format($totalDueAmount - $totalPaid, 2) }}
                            </p>
                        @elseif ($totalPaid > 0 && $totalPaid == $totalDueAmount)
                            <p class="text-xs text-emerald-600 mt-3 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                সম্পূর্ণ পরিশোধ — কোনো বকেয়া থাকবে না
                            </p>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- ────────────────────────────────────────────────────────────────
         Step 6: Receipt Modal
    ──────────────────────────────────────────────────────────────── --}}
    @if ($showReceipt && $lastPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.outside="$wire.closeReceipt()">
                {{-- Receipt Header --}}
                <div class="bg-emerald-600 text-white px-6 py-5 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">পেমেন্ট সফল!</h3>
                    <p class="text-emerald-100 text-sm">{{ $lastPayment->payment_no }}</p>
                </div>

                {{-- Receipt Body --}}
                <div class="px-6 py-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">শিক্ষার্থী</span>
                        <span class="font-medium text-gray-800">{{ $lastPayment->student->user->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">পরিশোধিত পরিমাণ</span>
                        <span class="font-bold text-emerald-700 text-base">৳{{ number_format($lastPayment->amount_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">তারিখ ও সময়</span>
                        <span class="font-medium text-gray-800">{{ $lastPayment->paid_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @if ($lastPayment->transaction_id)
                    <div class="flex justify-between">
                        <span class="text-gray-500">লেনদেন আইডি</span>
                        <span class="font-medium text-gray-800">{{ $lastPayment->transaction_id }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="px-6 pb-5 flex gap-3">
                    <a href="{{ route('admin.finance.receipt', $lastPayment->id) }}" target="_blank"
                        class="flex-1 text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        রসিদ প্রিন্ট করুন
                    </a>
                    <button wire:click="closeReceipt"
                        class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
