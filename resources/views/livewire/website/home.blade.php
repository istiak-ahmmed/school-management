@section('title', 'হোম')

<div>
    {{-- ── Hero Section ────────────────────────────────────────────────────── --}}
    <section class="relative bg-white overflow-hidden border-b border-gray-100">
        <div class="absolute inset-y-0 right-0 w-1/2 bg-emerald-50 rounded-l-full opacity-30 -mr-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-8">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-amber-100 text-amber-700 font-semibold text-sm">ভর্তি চলছে ২০২৬-২৭</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                        জ্ঞান ও নৈতিকতার <br>
                        <span class="theme-text">আদর্শ বিদ্যাপীঠ</span>
                    </h1>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        আমরা দ্বীনি শিক্ষার পাশাপাশি আধুনিক জ্ঞান-বিজ্ঞানে শিক্ষার্থীদের গড়ে তুলতে প্রতিশ্রুতিবদ্ধ। আজই আপনার সন্তানের উজ্জ্বল ভবিষ্যতের জন্য আমাদের সাথে যুক্ত হোন।
                    </p>
                    <div class="flex flex-wrap items-center gap-5 pt-6">
                        <a href="/admission" class="theme-bg text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl transition transform hover:-translate-y-1">
                            অনলাইন ভর্তি আবেদন
                        </a>
                        <a href="/about" class="bg-white text-emerald-700 border-2 border-emerald-100 px-8 py-3.5 rounded-xl font-bold text-lg hover:border-emerald-600 hover:bg-emerald-50 transition">
                            বিস্তারিত জানুন
                        </a>
                    </div>
                </div>
                <div class="hidden md:block relative">
                    <!-- Placeholder Hero Image / Pattern -->
                    <div class="aspect-square bg-gradient-to-tr from-emerald-100 to-amber-50 rounded-full flex items-center justify-center p-8 relative shadow-inner">
                        <div class="absolute inset-0 border-4 border-dashed border-emerald-200/50 rounded-full animate-[spin_60s_linear_infinite]"></div>
                        <svg class="w-1/2 h-1/2 text-emerald-600 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Quick Info Cards ──────────────────────────────────────────────── --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl shadow-gray-100/50 border border-gray-100 flex items-start gap-5 hover:border-emerald-200 transition">
                <div class="w-14 h-14 rounded-full theme-bg text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">দক্ষ শিক্ষকমণ্ডলী</h3>
                    <p class="text-sm text-gray-500">দেশ-বিদেশের স্বনামধন্য প্রতিষ্ঠান থেকে ডিগ্রিপ্রাপ্ত অভিজ্ঞ শিক্ষকমণ্ডলী দ্বারা পাঠদান।</p>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-xl shadow-gray-100/50 border border-gray-100 flex items-start gap-5 hover:border-emerald-200 transition">
                <div class="w-14 h-14 rounded-full gold-bg text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">আধুনিক সিলেবাস</h3>
                    <p class="text-sm text-gray-500">কুরআন, হাদিসের পাশাপাশি গণিত, বিজ্ঞান ও প্রযুক্তির যুগোপযোগী কারিকুলাম।</p>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-xl shadow-gray-100/50 border border-gray-100 flex items-start gap-5 hover:border-emerald-200 transition">
                <div class="w-14 h-14 rounded-full theme-bg text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">সুন্দর পরিবেশ</h3>
                    <p class="text-sm text-gray-500">শিক্ষার্থীদের জন্য সম্পূর্ণ রাজনীতিমুক্ত, কোলাহলমুক্ত ও মনোরম আবাসিক পরিবেশ।</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Notice Board Snippet ──────────────────────────────────────────── --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">সর্বশেষ নোটিশ</h2>
                <div class="w-24 h-1.5 gold-bg rounded"></div>
            </div>
            <a href="/notices" class="text-sm md:text-base font-semibold theme-text hover:text-emerald-800 flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-emerald-50 transition">
                সকল নোটিশ <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($notices as $notice)
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition group flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-xs font-semibold">
                            {{ $notice->publish_from ? $notice->publish_from->format('d M, Y') : 'N/A' }}
                        </div>
                        <div class="bg-gray-50 text-gray-600 px-3 py-1 rounded-lg text-xs font-medium border border-gray-100">
                            {{ ucfirst($notice->category) }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:theme-text transition line-clamp-2">
                        {{ $notice->title }}
                    </h3>
                    <p class="text-base text-gray-500 line-clamp-3 mb-6 flex-grow">
                        {{ $notice->content }}
                    </p>
                    <a href="/notices" class="inline-flex items-center text-sm font-bold theme-text group-hover:text-emerald-800">
                        বিস্তারিত পড়ুন <span class="ml-1 transition-transform group-hover:translate-x-1">&rarr;</span>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500">কোনো নোটিশ পাওয়া যায়নি।</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
