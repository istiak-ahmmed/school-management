@section('title', 'নোটিশ বোর্ড')

<div class="max-w-7xl mx-auto py-24 lg:py-32 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <span class="inline-block py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold mb-4">আপডেট</span>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 theme-text mb-6">নোটিশ বোর্ড</h1>
        <div class="w-32 h-1.5 gold-bg mx-auto rounded mb-8"></div>
    </div>

    <div class="flex flex-col md:flex-row gap-10">
        {{-- Sidebar Filters --}}
        <div class="w-full md:w-1/4">
            <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-gray-100/50 border border-gray-100 sticky top-28">
                <h3 class="font-bold text-gray-900 text-lg mb-6 border-b pb-4">সার্চ ও ফিল্টার</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">কীওয়ার্ড</label>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="নোটিশ খুঁজুন..." class="w-full text-sm rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">ক্যাটাগরি</label>
                        <select wire:model.live="category" class="w-full text-sm rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">সকল ক্যাটাগরি</option>
                            <option value="general">সাধারণ (General)</option>
                            <option value="academic">একাডেমিক (Academic)</option>
                            <option value="exam">পরীক্ষা (Exam)</option>
                            <option value="admission">ভর্তি (Admission)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notice List --}}
        <div class="w-full md:w-3/4 space-y-6">
            @forelse($notices as $notice)
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-100 transition flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-bold whitespace-nowrap">
                                {{ $notice->publish_from ? $notice->publish_from->format('d M, Y') : 'N/A' }}
                            </span>
                            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">
                                {{ $notice->category }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $notice->title }}</h3>
                        @if($notice->content)
                            <p class="text-base text-gray-500 mt-3 line-clamp-2">{{ $notice->content }}</p>
                        @endif
                    </div>
                    
                    <div class="flex-shrink-0">
                        @if($notice->attachment_path)
                            <a href="{{ Storage::url($notice->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 theme-bg text-white px-6 py-3 rounded-xl text-base font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                পিডিএফ
                            </a>
                        @else
                            <button type="button" class="inline-flex items-center gap-2 bg-gray-100 text-gray-400 px-6 py-3 rounded-xl text-base font-bold cursor-not-allowed">
                                কোনো ফাইল নেই
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-dashed border-gray-200">
                    <p class="text-gray-500">কোনো নোটিশ পাওয়া যায়নি।</p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $notices->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
