@section('title', 'ফলাফল')

<div class="max-w-5xl mx-auto py-20 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-100 p-10 md:p-16 lg:p-20">
        <div class="text-center mb-16">
            <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">যাচাই করুন</span>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">ভর্তি বা পরীক্ষার ফলাফল</h1>
            <div class="w-32 h-1.5 gold-bg mx-auto rounded"></div>
        </div>

        <div class="max-w-xl mx-auto">
            <form wire:submit="checkResult" class="mb-16">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-grow">
                        <label class="sr-only">আবেদন নম্বর / রোল</label>
                        <input wire:model="application_no" type="text" placeholder="আবেদন নম্বর দিন (যেমন: APP-2026-0001)" class="w-full px-6 py-4 rounded-2xl border-gray-200 text-lg focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-all" required>
                        @error('application_no') <span class="text-red-500 text-xs mt-2 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="theme-bg text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex items-center justify-center gap-2 flex-shrink-0">
                        <span wire:loading.remove wire:target="checkResult">ফলাফল দেখুন</span>
                        <span wire:loading wire:target="checkResult">খোঁজা হচ্ছে...</span>
                    </button>
                </div>
            </form>

            @if($hasSearched)
                <div class="mt-8">
                    @if($result)
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden shadow-inner">
                            <div class="bg-emerald-600 px-6 py-4">
                                <h3 class="text-white font-bold text-lg">আবেদনকারীর তথ্য</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-3 border-b border-gray-200 pb-3">
                                    <span class="text-gray-500 text-sm font-medium">আবেদন নম্বর</span>
                                    <span class="col-span-2 text-gray-900 font-semibold">{{ $result->application_no }}</span>
                                </div>
                                <div class="grid grid-cols-3 border-b border-gray-200 pb-3">
                                    <span class="text-gray-500 text-sm font-medium">নাম</span>
                                    <span class="col-span-2 text-gray-900 font-semibold">{{ $result->applicant_name }}</span>
                                </div>
                                <div class="grid grid-cols-3 border-b border-gray-200 pb-3">
                                    <span class="text-gray-500 text-sm font-medium">শ্রেণী</span>
                                    <span class="col-span-2 text-gray-900 font-semibold">{{ $result->applyingForClass->name ?? 'N/A' }}</span>
                                </div>
                                <div class="grid grid-cols-3 items-center">
                                    <span class="text-gray-500 text-sm font-medium">অবস্থা (Status)</span>
                                    <div class="col-span-2">
                                        @if($result->status === 'accepted' || $result->status === 'enrolled')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-100 text-green-800 font-bold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                ভর্তির জন্য নির্বাচিত
                                            </span>
                                        @elseif($result->status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 text-red-800 font-bold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                বাতিলকৃত
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 font-bold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                বিবেচনাধীন (Pending)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 text-red-600 p-6 rounded-2xl text-center border border-red-100">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="font-bold text-lg mb-1">কোনো তথ্য পাওয়া যায়নি</h3>
                            <p class="text-sm opacity-80">আপনার প্রদানকৃত আবেদন নম্বরটি সঠিক নয় অথবা সিস্টেম এখনো আপডেট হয়নি।</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
