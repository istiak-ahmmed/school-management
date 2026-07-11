@section('title', 'গ্যালারি')

<div class="max-w-7xl mx-auto py-24 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">ছবি ও ভিডিও</span>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">ফটো গ্যালারি</h1>
        <div class="w-32 h-1.5 gold-bg mx-auto rounded mb-8"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
        @forelse($galleries as $item)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden group border border-gray-100 hover:shadow-lg transition">
                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                    {{-- Placeholder since actual images might not exist locally for the demo --}}
                    @if($item->path && file_exists(public_path($item->path)))
                        <img src="{{ asset($item->path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                        {{-- Fallback placeholder --}}
                        <div class="absolute inset-0 bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 flex items-end p-6">
                        <h3 class="text-white font-bold text-lg line-clamp-2 transform translate-y-4 group-hover:translate-y-0 transition duration-500">{{ $item->title }}</h3>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl shadow-sm border border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p class="text-gray-500">গ্যালারিতে এখনো কোনো ছবি যোগ করা হয়নি।</p>
            </div>
        @endforelse
    </div>
</div>
