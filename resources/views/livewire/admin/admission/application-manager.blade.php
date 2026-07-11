@section('header', 'ভর্তি আবেদন পর্যালোচনা')

<div class="space-y-5">

    {{-- Flash Notification --}}
    @if(session()->has('notify'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="p-4 bg-green-50 text-green-800 rounded-xl border border-green-200 flex justify-between items-center">
            <span>{{ session('notify') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700">✕</button>
        </div>
    @endif

    {{-- ── Filters & Search ──────────────────────────────────────────────── --}}
    @if(!$viewingId)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.400ms="search"
                       type="search" id="app-search"
                       placeholder="নাম, আবেদন নম্বর বা ফোন দিয়ে খুঁজুন..."
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-200">
            </div>
            <select wire:model.live="filterStatus" id="filter-status"
                    class="rounded-xl border-gray-200 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-200">
                <option value="">-- সকল স্ট্যাটাস --</option>
                <option value="1">মুলতবি</option>
                <option value="2">পর্যালোচনাধীন</option>
                <option value="3">গৃহীত</option>
                <option value="4">প্রত্যাখ্যাত</option>
                <option value="5">ভর্তিকৃত</option>
            </select>
            <select wire:model.live="filterClass" id="filter-class"
                    class="rounded-xl border-gray-200 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-200">
                <option value="">-- সকল শ্রেণী --</option>
                @foreach($this->classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ── Application Table ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">আবেদন নম্বর</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">শিক্ষার্থীর নাম</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">শ্রেণী</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">অভিভাবক / ফোন</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">তারিখ</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($this->applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-semibold text-indigo-600">{{ $app->application_no }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $app->applicant_name }}</div>
                                @if($app->dob)
                                    <div class="text-xs text-gray-400">জন্ম: {{ $app->dob->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $app->applyingForClass?->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $app->guardian_name ?? '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $app->guardian_phone ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400">
                                {{ $app->submitted_at?->format('d M Y') ?? $app->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $app->status_color }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($app->status === 1)
                                        <button wire:click="markUnderReview({{ $app->id }})"
                                                wire:confirm="এই আবেদনটি পর্যালোচনাধীন হিসেবে চিহ্নিত করবেন?"
                                                class="text-xs px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                            পর্যালোচনা শুরু
                                        </button>
                                    @endif
                                    <button wire:click="viewApplication({{ $app->id }})"
                                            class="text-xs px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                                        বিস্তারিত
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <div class="text-4xl mb-3">📋</div>
                                <p class="text-sm">কোনো আবেদন পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->applications->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $this->applications->links() }}
            </div>
        @endif
    </div>
    @endif

    {{-- ── Detail / Review View ──────────────────────────────────────────── --}}
    @if($viewingId && $this->viewingApplication)
        @php $app = $this->viewingApplication; @endphp
        <div class="space-y-5">
            {{-- Back button --}}
            <div class="flex items-center gap-3">
                <button wire:click="closeDetail" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    সকল আবেদন
                </button>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-gray-700">{{ $app->application_no }}</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $app->status_color }}">
                    {{ $app->status_label }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Left: Application Details --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3">👤 শিক্ষার্থীর তথ্য</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">নাম:</span> <strong>{{ $app->applicant_name }}</strong></div>
                            <div><span class="text-gray-500">জন্ম তারিখ:</span> <strong>{{ $app->dob?->format('d/m/Y') ?? '—' }}</strong></div>
                            <div><span class="text-gray-500">লিঙ্গ:</span> <strong>{{ match($app->gender) {1=>'ছেলে',2=>'মেয়ে',3=>'অন্যান্য',default=>'—'} }}</strong></div>
                            <div><span class="text-gray-500">ভর্তি ইচ্ছুক শ্রেণী:</span> <strong>{{ $app->applyingForClass?->name ?? '—' }}</strong></div>
                            <div><span class="text-gray-500">শিক্ষাবর্ষ:</span> <strong>{{ $app->academicYear?->name ?? '—' }}</strong></div>
                            <div><span class="text-gray-500">পূর্ববর্তী বিদ্যালয়:</span> <strong>{{ $app->previous_school ?? '—' }}</strong></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3">👨‍👩‍👧 অভিভাবকের তথ্য</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">নাম:</span> <strong>{{ $app->guardian_name ?? '—' }}</strong></div>
                            <div><span class="text-gray-500">ফোন:</span> <strong>{{ $app->guardian_phone ?? '—' }}</strong></div>
                            <div><span class="text-gray-500">ইমেইল:</span> <strong>{{ $app->guardian_email ?? '—' }}</strong></div>
                            <div class="col-span-2"><span class="text-gray-500">ঠিকানা:</span> <strong>{{ $app->address ?? '—' }}</strong></div>
                        </div>
                    </div>

                    @if($app->review_note && in_array($app->status, [3, 4, 5]))
                        <div class="bg-{{ $app->status === 4 ? 'red' : 'green' }}-50 rounded-xl border border-{{ $app->status === 4 ? 'red' : 'green' }}-100 p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-1">পর্যালোচনা নোট:</p>
                            <p class="text-sm text-gray-600">{{ $app->review_note }}</p>
                            @if($app->reviewer)
                                <p class="text-xs text-gray-400 mt-2">পর্যালোচক: {{ $app->reviewer->name }} — {{ $app->reviewed_at?->format('d M Y, h:i A') }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Right: Actions --}}
                <div class="space-y-4">
                    @if(in_array($app->status, [1, 2]))
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-3">
                        <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3">⚡ অ্যাকশন</h3>

                        @if(!$showReview)
                            <button wire:click="prepareReview('accept')"
                                    class="w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition flex items-center justify-center gap-2">
                                ✓ আবেদন গ্রহণ করুন
                            </button>
                            <button wire:click="prepareReview('reject')"
                                    class="w-full px-4 py-2.5 bg-red-50 text-red-700 text-sm font-medium rounded-xl hover:bg-red-100 transition border border-red-200 flex items-center justify-center gap-2">
                                ✕ আবেদন প্রত্যাখ্যান করুন
                            </button>
                        @else
                            <div class="space-y-3">
                                <div class="p-3 rounded-xl {{ $reviewAction === 'accept' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                    <p class="text-sm font-semibold {{ $reviewAction === 'accept' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $reviewAction === 'accept' ? '✓ আবেদন গ্রহণ নিশ্চিত করুন' : '✕ আবেদন প্রত্যাখ্যান করুন' }}
                                    </p>
                                </div>

                                @if($reviewAction === 'reject')
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">প্রত্যাখ্যানের কারণ <span class="text-red-500">*</span></label>
                                        <textarea wire:model="reviewNote" rows="4"
                                                  placeholder="প্রত্যাখ্যানের কারণ লিখুন..."
                                                  class="w-full rounded-xl text-sm border-gray-200 focus:border-red-400 focus:ring-red-100 resize-none"></textarea>
                                        @error('reviewNote') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500">আবেদন গ্রহণ করলে স্বয়ংক্রিয়ভাবে ছাত্রের অ্যাকাউন্ট ও রেকর্ড তৈরি হবে এবং ভর্তি নম্বর জেনারেট হবে।</p>
                                @endif

                                <div class="flex gap-2">
                                    <button wire:click="submitReview"
                                            wire:loading.attr="disabled"
                                            class="flex-1 px-3 py-2 {{ $reviewAction === 'accept' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white text-sm font-medium rounded-xl transition">
                                        <span wire:loading.remove wire:target="submitReview">নিশ্চিত করুন</span>
                                        <span wire:loading wire:target="submitReview">অপেক্ষা...</span>
                                    </button>
                                    <button wire:click="$set('showReview', false)"
                                            class="flex-1 px-3 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition">
                                        বাতিল
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    @else
                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-5 text-center text-sm text-gray-500">
                            এই আবেদনটি ইতিমধ্যে <strong>{{ $app->status_label }}</strong> হয়েছে।
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
