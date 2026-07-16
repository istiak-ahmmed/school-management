<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-orange-100 max-w-md w-full text-center">
        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">প্রোফাইল অসম্পূর্ণ</h3>
        <p class="text-gray-500 text-sm mb-6">আপনার অ্যাকাউন্টটি এখনও কোনো শিক্ষকের প্রোফাইলের সাথে লিঙ্ক করা হয়নি। অনুগ্রহ করে স্কুল কর্তৃপক্ষের সাথে যোগাযোগ করুন।</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-medium py-2 px-4 rounded-lg transition">
                লগ আউট করুন
            </button>
        </form>
    </div>
</div>
