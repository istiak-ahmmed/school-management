@section('title', 'যোগাযোগ')

<div class="max-w-7xl mx-auto py-24 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">আমাদের সাথে যুক্ত থাকুন</span>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">যোগাযোগ করুন</h1>
        <div class="w-32 h-1.5 gold-bg mx-auto rounded mb-8"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
        {{-- Contact Info & Map --}}
        <div class="space-y-10">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 p-10 lg:p-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">আমাদের ঠিকানা</h3>
                
                <ul class="space-y-8">
                    <li class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">অবস্থান</h4>
                            <p class="text-gray-600 text-sm">১২৩, ইসলামী সড়ক, ঢাকা, বাংলাদেশ</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ফোন</h4>
                            <p class="text-gray-600 text-sm">+৮৮০ ১৯০০-০০০০০০ <br> +৮৮০ ১৯০০-০০০০০১</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">ইমেইল</h4>
                            <p class="text-gray-600 text-sm">info@madrasha.edu.bd</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            {{-- Google Map --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden h-72">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14608.27361839219!2d90.36440626359555!3d23.744955747683935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b087026b81%3A0x8fa563bbdd5904c2!2sDhaka!5e0!3m2!1sen!2sbd!4v1700000000000!5m2!1sen!2sbd" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 p-10 lg:p-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">আমাদের বার্তা পাঠান</h3>
            
            <form wire:submit="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">আপনার নাম <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" required>
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ইমেইল</label>
                        <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ফোন নম্বর</label>
                        <input wire:model="phone" type="text" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">বিষয় <span class="text-red-500">*</span></label>
                    <input wire:model="subject" type="text" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" required>
                    @error('subject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">আপনার বার্তা <span class="text-red-500">*</span></label>
                    <textarea wire:model="message" rows="5" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" required></textarea>
                    @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full theme-bg text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex justify-center items-center gap-2">
                        <span wire:loading.remove wire:target="submit">বার্তা পাঠান</span>
                        <span wire:loading wire:target="submit">পাঠানো হচ্ছে...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
