<div>
    @section('header', 'বেতন পরিশোধ ব্যবস্থাপনা')

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @php
        $totalExpected = collect($this->employees)->sum('net_salary');
        $totalPaid = collect($this->employees)->where('status', \App\Enums\SalaryStatus::Paid)->sum('net_salary'); // Approx paid amount
        $totalAdvance = collect($this->employees)->sum('pending_advance');
    @endphp

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">মোট প্রত্যাশিত বেতন</p>
            <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalExpected, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">মোট পরিশোধিত</p>
            <p class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">অগ্রিম বকেয়া</p>
            <p class="text-2xl font-bold text-red-500">৳{{ number_format($totalAdvance, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-gray-800">শিক্ষক ও কর্মচারীদের বেতন</h2>
            
            <div class="flex items-center gap-3">
                <select wire:model.live="employeeTypeFilter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">সকল ধরন</option>
                    @foreach (\App\Enums\EmployeeType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>

                <input type="month" wire:model.live="monthFilter"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
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
                        <th class="px-5 py-3 font-medium">নাম ও পদবি</th>
                        <th class="px-5 py-3 font-medium text-center">ধরন</th>
                        <th class="px-5 py-3 font-medium text-right">মূল বেতন</th>
                        <th class="px-5 py-3 font-medium text-right">নেট বেতন (প্রাপ্য)</th>
                        <th class="px-5 py-3 font-medium text-right text-red-500">অগ্রিম বকেয়া</th>
                        <th class="px-5 py-3 font-medium text-center">অবস্থা</th>
                        <th class="px-5 py-3 font-medium text-center">একশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($this->employees as $emp)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ $emp['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $emp['designation'] }}</div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $emp['type']->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600">৳{{ number_format($emp['basic_salary'], 2) }}</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-800">৳{{ number_format($emp['net_salary'], 2) }}</td>
                            <td class="px-5 py-3 text-right text-red-500 font-medium">
                                {{ $emp['pending_advance'] > 0 ? '৳' . number_format($emp['pending_advance'], 2) : '—' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($emp['status']->value === 0)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">পরিশোধিত</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">অপেক্ষমাণ</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($emp['status']->value === 0)
                                    <a href="{{ route('admin.finance.salary-slip', $emp['payment']->id) }}" target="_blank"
                                       class="text-indigo-600 hover:text-indigo-800 text-xs font-medium transition">স্লিপ দেখুন</a>
                                @else
                                    <button wire:click="openPaymentModal({{ $emp['id'] }}, {{ $emp['type']->value }})"
                                            class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition">
                                        বেতন পরিশোধ
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">কোনো শিক্ষক বা কর্মচারী পাওয়া যায়নি</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment Modal --}}
    @if ($showPaymentModal && $selectedEmployee)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data>
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.outside="$wire.closePaymentModal()">
                
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-lg">বেতন পরিশোধ - {{ $monthFilter }}</h3>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Employee Info Summary --}}
                    <div class="bg-indigo-50 p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-bold text-indigo-900">{{ $selectedEmployee['name'] }}</p>
                            <p class="text-xs text-indigo-700">{{ $selectedEmployee['type']->label() }} | {{ $selectedEmployee['designation'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-indigo-700 mb-1">মোট প্রাপ্য (নেট বেতন)</p>
                            <p class="font-bold text-lg text-indigo-900">৳{{ number_format($selectedEmployee['net_salary'], 2) }}</p>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Advance Deduction Option --}}
                    @if ($selectedEmployee['pending_advance'] > 0)
                        <div class="p-4 border border-red-200 bg-red-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-red-800 text-sm">অগ্রিম বকেয়া আছে: ৳{{ number_format($selectedEmployee['pending_advance'], 2) }}</span>
                                <label class="flex items-center gap-2 cursor-pointer text-sm text-red-700">
                                    <input type="checkbox" wire:model.live="deductAdvance" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                    অগ্রিম কর্তন করুন
                                </label>
                            </div>
                            
                            @if ($deductAdvance)
                                <div class="mt-3 flex items-center gap-3">
                                    <label class="text-xs text-red-700 font-medium whitespace-nowrap">কর্তনের পরিমাণ:</label>
                                    <input type="number" wire:model.live.debounce.500ms="advanceToDeduct" 
                                           max="{{ min($selectedEmployee['pending_advance'], $selectedEmployee['net_salary']) }}"
                                           class="w-full border border-red-200 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">প্রদানের পরিমাণ (৳)</label>
                            <input type="number" wire:model="amountToPay" readonly
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-100 font-bold text-gray-800">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">পেমেন্ট পদ্ধতি</label>
                            <select wire:model="paymentMethod" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->bn_name ?? $method->en_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">ট্রানজেকশন আইডি / চেক (ঐচ্ছিক)</label>
                            <input type="text" wire:model="transactionId" 
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">মন্তব্য (ঐচ্ছিক)</label>
                            <input type="text" wire:model="note" 
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closePaymentModal" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                        বাতিল
                    </button>
                    <button wire:click="processPayment" wire:loading.attr="disabled"
                            class="flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="processPayment">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span wire:loading wire:target="processPayment">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </span>
                        বেতন প্রদান করুন
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Salary Slip Modal Success --}}
    @if ($showSlip && $lastPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" @click.outside="$wire.closeSlip()">
                <div class="bg-indigo-600 text-white px-6 py-5 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">বেতন প্রদান সফল!</h3>
                    <p class="text-indigo-100 text-sm">ভাউচার: {{ $lastPayment->voucher_no }}</p>
                </div>
                
                <div class="px-6 py-6 flex gap-3 flex-col">
                    <a href="{{ route('admin.finance.salary-slip', $lastPayment->id) }}" target="_blank"
                        class="w-full text-center py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-sm font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        বেতন স্লিপ ডাউনলোড
                    </a>
                    <button wire:click="closeSlip"
                        class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
