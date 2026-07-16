<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">নোটিশ বোর্ড</h2>
            <p class="text-sm text-gray-500">শিক্ষকদের জন্য এবং আপনার ক্লাসের নোটিশ</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select wire:model.live="category" class="rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm">
                <option value="">সকল ক্যাটাগরি</option>
                <option value="general">সাধারণ (General)</option>
                <option value="academic">একাডেমিক (Academic)</option>
                <option value="exam">পরীক্ষা (Exam)</option>
                <option value="event">ইভেন্ট (Event)</option>
                <option value="holiday">ছুটি (Holiday)</option>
            </select>
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="নোটিশ খুঁজুন..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm pl-10">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @forelse($notices as $notice)
            <div class="p-6 border-b border-gray-100 last:border-b-0 hover:bg-violet-50/20 transition {{ $notice->is_pinned ? 'border-l-4 border-l-orange-400' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="hidden sm:flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 min-w-[70px] border border-gray-100 shrink-0">
                        <span class="text-xs font-semibold uppercase text-gray-500">{{ $notice->created_at->format('M') }}</span>
                        <span class="text-2xl font-bold leading-none my-1 text-gray-800">{{ $notice->created_at->format('d') }}</span>
                        <span class="text-xs text-gray-400">{{ $notice->created_at->format('Y') }}</span>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full
                                    {{ $notice->category === 'exam' ? 'bg-red-100 text-red-800' :
                                      ($notice->category === 'academic' ? 'bg-blue-100 text-blue-800' :
                                      ($notice->category === 'holiday' ? 'bg-green-100 text-green-800' :
                                      'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($notice->category) }}
                                </span>
                                <span class="sm:hidden text-xs text-gray-400">{{ $notice->created_at->format('d M, Y') }}</span>
                            </div>
                            <span class="text-xs text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                            @if($notice->is_pinned)
                                <svg class="w-4 h-4 text-orange-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            @endif
                            {{ $notice->title }}
                        </h3>

                        <div class="text-sm text-gray-600">
                            {!! nl2br(e($notice->body)) !!}
                        </div>

                        @if($notice->attachment_path)
                            <div class="mt-4">
                                <a href="{{ Storage::url($notice->attachment_path) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-violet-200 shadow-sm text-sm font-medium rounded-md text-violet-700 bg-violet-50 hover:bg-violet-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    অ্যাটাচমেন্ট ডাউনলোড
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো নোটিশ নেই</h3>
                <p class="text-sm text-gray-500">আপনার জন্য কোনো নোটিশ পাওয়া যায়নি।</p>
            </div>
        @endforelse

        @if($notices->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $notices->links() }}
            </div>
        @endif
    </div>
</div>
