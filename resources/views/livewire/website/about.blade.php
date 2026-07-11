@section('title', 'আমাদের সম্পর্কে')

<div class="max-w-5xl mx-auto py-20 lg:py-32 px-4 sm:px-6">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-100 p-10 md:p-16 lg:p-20">
        <div class="text-center mb-16">
            <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">পরিচিতি</span>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">আমাদের সম্পর্কে</h1>
            <div class="w-32 h-1.5 gold-bg mx-auto rounded"></div>
        </div>

        <div class="space-y-16 lg:space-y-24">
            {{-- History --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">মাদ্রাসার ইতিহাস</h2>
                </div>
                <div class="prose prose-emerald max-w-none text-gray-600 leading-relaxed">
                    <p>
                        প্রতিষ্ঠালগ্ন থেকেই আমাদের মাদ্রাসা একটি আদর্শ ও যুগোপযোগী শিক্ষাপ্রতিষ্ঠান হিসেবে সুনাম অর্জন করেছে। ইসলামী মূল্যবোধ ও আধুনিক শিক্ষার সমন্বয়ে শিক্ষার্থীদের একটি আলোকিত জীবনের পথ দেখাতে আমরা কাজ করে যাচ্ছি।
                    </p>
                    <p>
                        অত্র অঞ্চলে দ্বীনি শিক্ষার প্রচার ও প্রসারে এই প্রতিষ্ঠান গুরুত্বপূর্ণ ভূমিকা পালন করছে। সুদক্ষ পরিচালনা পর্ষদ ও অভিজ্ঞ শিক্ষকমণ্ডলীর তত্ত্বাবধানে প্রতিষ্ঠানটি প্রতিনিয়ত সামনের দিকে এগিয়ে যাচ্ছে।
                    </p>
                </div>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                {{-- Vision --}}
                <section class="bg-emerald-50/50 p-8 lg:p-10 rounded-3xl border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full theme-bg flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">আমাদের ভিশন</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        কুরআন ও সুন্নাহর আলোকে সৎ, যোগ্য, এবং দেশপ্রেমিক নাগরিক তৈরি করা। আধুনিক বিশ্বের চ্যালেঞ্জ মোকাবেলায় শিক্ষার্থীদের প্রস্তুত করা এবং নৈতিকতার সর্বোচ্চ মাপকাঠিতে উত্তীর্ণ হওয়া।
                    </p>
                </section>

                {{-- Mission --}}
                <section class="bg-amber-50/50 p-8 lg:p-10 rounded-3xl border border-amber-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full gold-bg flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">আমাদের মিশন</h2>
                    </div>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>মানসম্মত ও যুগোপযোগী শিক্ষাদান নিশ্চিত করা।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>শিক্ষার্থীদের মানসিক ও আধ্যাত্মিক বিকাশ ঘটানো।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>সৃজনশীলতা এবং নেতৃত্বের গুণাবলী তৈরি করা।</span>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</div>
