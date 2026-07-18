@section('title', 'শিক্ষক ও স্টাফ')

<div class="max-w-7xl mx-auto py-24 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">আমাদের টিম</span>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">শিক্ষক ও স্টাফ</h1>
        <div class="w-32 h-1.5 gold-bg mx-auto rounded mb-8"></div>
        <p class="max-w-2xl mx-auto text-lg text-gray-600">আমাদের একনিষ্ঠ এবং দক্ষ শিক্ষকমণ্ডলী ও স্টাফ সর্বদা শিক্ষার্থীদের উজ্জ্বল ভবিষ্যত গড়তে নিবেদিত।</p>
    </div>

    {{-- Search Bar --}}
    <div class="max-w-2xl mx-auto mb-16">
        <div class="relative shadow-sm rounded-2xl">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="নাম দিয়ে খুঁজুন..." class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 text-lg focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Teachers Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($teachers as $teacher)
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition group text-center p-8 relative">
                {{-- Decorative background --}}
                <div class="absolute inset-0 bg-gradient-to-b from-emerald-50/50 to-transparent h-32"></div>
                
                <div class="relative w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white shadow-md">
                    @if($teacher->photo_path)
                        <img src="{{ Storage::url($teacher->photo_path) }}" alt="{{ $teacher->user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-3xl font-bold">
                            {{ mb_substr($teacher->user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 group-hover:theme-text transition mb-1">{{ $teacher->user->name }}</h3>
                <p class="text-sm text-emerald-600 font-medium mb-2">{{ $teacher->designation ?? 'শিক্ষক' }}</p>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl shadow-sm border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-gray-500">কোনো শিক্ষক বা স্টাফ পাওয়া যায়নি।</p>
            </div>
        @endforelse
    </div>
</div>
