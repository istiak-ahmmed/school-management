<div>
    @section('header', 'খরচের রিপোর্ট')

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 print:hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">রিপোর্ট ফিল্টার</h3>
            <a href="{{ route('admin.finance.expenses.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-semibold">
                তালিকায় ফিরে যান
            </a>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ হতে</label>
                <input type="date" wire:model.live="date_from" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ পর্যন্ত</label>
                <input type="date" wire:model.live="date_to" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">খাত নির্বাচন</label>
                <select wire:model.live="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">সকল খাত</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button onclick="window.print()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg px-4 py-2 transition flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    প্রিন্ট / PDF
                </button>
                <button wire:click="downloadCsv" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg px-4 py-2 transition flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    এক্সেল (Excel)
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Report Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:text-black">
        <div class="p-6 border-b border-gray-100 text-center print:border-black">
            <h1 class="text-2xl font-bold mb-2 text-gray-800 print:text-black">খরচের রিপোর্ট</h1>
            <p class="text-sm text-gray-500 print:text-black">
                হতে: {{ $date_from ? \Carbon\Carbon::parse($date_from)->format('d M, Y') : 'শুরু' }} 
                পর্যন্ত: {{ $date_to ? \Carbon\Carbon::parse($date_to)->format('d M, Y') : 'শেষ' }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider print:bg-transparent print:border-black">
                    <tr>
                        <th class="px-5 py-3 font-medium print:text-black">তারিখ</th>
                        <th class="px-5 py-3 font-medium print:text-black">ভাউচার নং</th>
                        <th class="px-5 py-3 font-medium print:text-black">খাত</th>
                        <th class="px-5 py-3 font-medium print:text-black">প্রাপক</th>
                        <th class="px-5 py-3 font-medium print:text-black">মন্তব্য</th>
                        <th class="px-5 py-3 font-medium text-right print:text-black">পরিমাণ (৳)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 print:divide-gray-400">
                    @php $total = 0; @endphp
                    @forelse ($this->expenses as $expense)
                        @php $total += $expense->amount; @endphp
                        <tr>
                            <td class="px-5 py-3 text-gray-900 print:text-black">{{ $expense->expense_date->format('d M, Y') }}</td>
                            <td class="px-5 py-3 text-gray-500 print:text-black">{{ $expense->voucher_no }}</td>
                            <td class="px-5 py-3 text-gray-900 font-medium print:text-black">{{ optional($expense->category)->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500 print:text-black">{{ $expense->paid_to }}</td>
                            <td class="px-5 py-3 text-gray-500 print:text-black">{{ Str::limit($expense->note, 30) }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900 print:text-black">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-4 text-center text-gray-500 print:text-black">এই সময়ের মধ্যে কোনো খরচ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200 print:bg-transparent print:border-black">
                    <tr>
                        <th colspan="5" class="px-5 py-4 text-right text-sm font-bold text-gray-700 print:text-black">মোট খরচ:</th>
                        <th class="px-5 py-4 text-right text-lg font-bold text-gray-900 print:text-black">৳ {{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="px-6 py-4 text-center text-xs text-gray-500 hidden print:block">
            রিপোর্ট তৈরি হয়েছে: {{ now()->format('d M, Y h:i A') }}
        </div>
    </div>
</div>
