@section('title', 'ভর্তি তথ্য')

<div class="max-w-5xl mx-auto py-20 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-100 p-10 md:p-16 lg:p-20 text-center">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">ভর্তি প্রক্রিয়া</span>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">ভর্তি সংক্রান্ত বিস্তারিত তথ্য</h1>
        <div class="w-32 h-1.5 gold-bg mx-auto rounded mb-10"></div>
        
        <p class="text-lg text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
            মাদ্রাসার নতুন শিক্ষাবর্ষের ভর্তি কার্যক্রম শুরু হয়েছে। অনলাইনে আবেদন ফর্ম পূরণের মাধ্যমে আপনি খুব সহজেই ভর্তির আবেদন করতে পারবেন।
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left mb-16">
            <div class="bg-gray-50/50 rounded-3xl p-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    ভর্তির যোগ্যতা
                </h3>
                <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                    <li>নূরানী, নাজেরা ও হেফজ বিভাগে ৫-১২ বছর।</li>
                    <li>আগের শ্রেণীর প্রশংসাপত্র (যদি থাকে)।</li>
                    <li>জন্ম নিবন্ধন বা স্মার্ট কার্ডের কপি।</li>
                </ul>
            </div>
            
            <div class="bg-gray-50/50 rounded-3xl p-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    প্রয়োজনীয় কাগজপত্র
                </h3>
                <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                    <li>শিক্ষার্থীর ২ কপি পাসপোর্ট সাইজ ছবি।</li>
                    <li>পিতা/মাতার ভোটার আইডি কার্ডের কপি।</li>
                    <li>ভর্তি ফরমের প্রিন্ট কপি (অনলাইন আবেদনের পর)।</li>
                </ul>
            </div>
        </div>

        <div class="bg-emerald-50/50 rounded-[2rem] p-10 md:p-12 border border-emerald-100/50 text-center shadow-inner">
            <h3 class="text-2xl font-bold text-emerald-900 mb-4">অনলাইন ভর্তি আবেদন করুন</h3>
            <p class="text-emerald-700 text-base mb-8 max-w-xl mx-auto">
                কাগজে-কলমে ভর্তি প্রক্রিয়া এড়িয়ে ঘরে বসেই আবেদন সম্পূর্ণ করুন। আবেদন করতে নিচের বাটনে ক্লিক করুন।
            </p>
            <a href="/admission" class="inline-block theme-bg text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl transition transform hover:-translate-y-1">
                ভর্তি আবেদন ফরম
            </a>
        </div>
    </div>
</div>
