<div>
    @section('header', $expenseId ? 'খরচ আপডেট' : 'খরচ এন্ট্রি')

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ $expenseId ? 'খরচের তথ্য আপডেট করুন' : 'নতুন খরচ এন্ট্রি করুন' }}
            </h3>
        </div>
        <div class="p-6">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Voucher No -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ভাউচার নং <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="voucher_no" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('voucher_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Expense Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">খরচের খাত <span class="text-red-500">*</span></label>
                    <select wire:model="expense_category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">খাত নির্বাচন করুন</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('expense_category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">পরিমাণ (৳) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" wire:model="amount" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">তারিখ <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="expense_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('expense_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">পেমেন্ট মেথড <span class="text-red-500">*</span></label>
                    <select wire:model="payment_method_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->bn_name ?? $method->en_name }}</option>
                        @endforeach
                    </select>
                    @error('payment_method_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Paid To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">প্রাপক (Paid To)</label>
                    <input type="text" wire:model="paid_to" placeholder="যাকে প্রদান করা হয়েছে..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('paid_to') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Receipt Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">রশিদ/ভাউচার ফাইলসমূহ (ঐচ্ছিক)</label>
                    <input type="file" wire:model="receipts" multiple accept="image/*,.pdf" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 cursor-pointer">
                    <div wire:loading wire:target="receipts" class="text-sm text-indigo-500 mt-1">আপলোড হচ্ছে...</div>
                    @error('receipts.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    @if ($receipts)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($receipts as $receipt)
                                @if(in_array($receipt->extension(), ['jpg', 'jpeg', 'png', 'webp']))
                                    <img src="{{ $receipt->temporaryUrl() }}" class="h-20 w-auto rounded-lg border border-gray-200 shadow-sm">
                                @else
                                    <div class="h-20 w-20 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200 shadow-sm">
                                        <span class="text-xs font-semibold text-gray-500">PDF</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if (count($existingMedia) > 0)
                        <div class="mt-4">
                            <span class="text-sm text-gray-500 block mb-2">বর্তমান ফাইলসমূহ:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($existingMedia as $media)
                                    <div class="relative group">
                                        @if(Str::startsWith($media->mime_type, 'image/'))
                                            <img src="{{ $media->getUrl('optimized') }}" class="h-20 w-auto rounded-lg border border-gray-200 shadow-sm">
                                        @else
                                            <div class="h-20 w-20 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200 shadow-sm">
                                                <span class="text-xs font-semibold text-gray-500">PDF</span>
                                            </div>
                                        @endif
                                        <button type="button" wire:click="removeExistingMedia({{ $media->id }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 focus:outline-none hidden group-hover:block" title="রিমুভ করুন">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">নোট/মন্তব্য</label>
                    <textarea wire:model="note" rows="3" placeholder="অতিরিক্ত কোনো তথ্য থাকলে লিখুন..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                    @error('note') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('admin.finance.expenses.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    বাতিল
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    {{ $expenseId ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
