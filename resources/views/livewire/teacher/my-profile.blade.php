<div x-data="{ showGeneralModal: false, showPasswordModal: false }" 
     @close-modal.window="if($event.detail.id === 'general-info-modal') showGeneralModal = false; if($event.detail.id === 'password-modal') showPasswordModal = false;">
    
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">আমার প্রোফাইল</h2>
            <p class="text-sm text-gray-500">শিক্ষকের ব্যক্তিগত এবং পেশাগত তথ্য</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="showGeneralModal = true" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                তথ্য আপডেট
            </button>
            <button @click="showPasswordModal = true" class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                পাসওয়ার্ড পরিবর্তন
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <x-shared.profile-header
            color="violet"
            :photoPath="$teacher->photo_path"
            :fallbackInitial="mb_substr(auth()->user()->name, 0, 1)"
            :title="auth()->user()->name"
            :subtitle="'পদবি: ' . ($teacher->designation ?? 'শিক্ষক')"
        >
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">আইডি: {{ $teacher->employee_id ?? 'N/A' }}</span>
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">ডিপার্টমেন্ট: {{ $teacher->department ?? 'সাধারণ' }}</span>
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">ফোন: {{ $teacher->phone ?? 'N/A' }}</span>
            @if($formTeacherSections->isNotEmpty())
                <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                    ফর্ম টিচার
                </span>
            @endif
        </x-shared.profile-header>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Info -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        ব্যক্তিগত তথ্য
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">পূর্ণ নাম</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">ইমেইল</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ auth()->user()->email }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">মোবাইল নম্বর</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ auth()->user()->phone ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">যোগদানের তারিখ</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d F Y') : '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">চাকরির ধরন</dt>
                            <dd class="text-sm font-semibold text-gray-800">
                                @php
                                    $contractType = match((string) $teacher->contract_type) {
                                        '1' => 'স্থায়ী (Permanent)',
                                        '2' => 'চুক্তিভিত্তিক (Contractual)',
                                        '3' => 'খণ্ডকালীন (Part-time)',
                                        default => '-',
                                    };
                                @endphp
                                {{ $contractType }}
                            </dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">এনআইডি</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $teacher->nid ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Academic / Professional Info -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        পেশাগত তথ্য
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">পদবি</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $teacher->designation ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">বিশেষত্ব</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $teacher->specialization ?? '-' }}</dd>
                        </div>
                        @if(is_array($teacher->qualification))
                            <div class="border-b border-gray-50 pb-2">
                                <dt class="text-sm text-gray-500 mb-2">শিক্ষাগত যোগ্যতা</dt>
                                <dd>
                                    @foreach($teacher->qualification as $q)
                                        <span class="inline-block px-2 py-0.5 mb-1 text-xs font-medium rounded-full bg-violet-50 text-violet-700 border border-violet-100">{{ $q }}</span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </dl>

                    @if($formTeacherSections->isNotEmpty())
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                            <h5 class="text-sm font-bold text-blue-800 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                ফর্ম টিচার হিসেবে নিয়োজিত
                            </h5>
                            @foreach($formTeacherSections as $sec)
                                <div class="flex items-center gap-2 text-sm text-blue-700 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                    {{ $sec->schoolClass->name ?? '' }} - {{ $sec->name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Teaching Assignments -->
            @if($teachingAssignments->count() > 0)
                <div class="mt-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        বিষয় নিয়োগ (Teaching Assignments)
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach($teachingAssignments as $assignment)
                            <div class="flex items-center gap-2 bg-violet-50 border border-violet-100 rounded-lg px-4 py-2">
                                <div class="w-2 h-2 rounded-full bg-violet-500 shrink-0"></div>
                                <span class="text-sm font-semibold text-violet-800">{{ $assignment->subject_name }}</span>
                                <span class="text-xs text-violet-600">— {{ $assignment->class_name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- General Info Update Modal -->
    <div x-show="showGeneralModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showGeneralModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showGeneralModal = false"></div>
            
            <div x-show="showGeneralModal" x-transition class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    সাধারণ তথ্য আপডেট
                </h3>
                
                <form wire:submit="updateGeneralInfo">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">মোবাইল নম্বর</label>
                        <input type="text" wire:model="phone" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showGeneralModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            বাতিল
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-violet-600 rounded-lg hover:bg-violet-700 flex items-center">
                            <span wire:loading.remove wire:target="updateGeneralInfo">সেভ করুন</span>
                            <span wire:loading wire:target="updateGeneralInfo">সেভ হচ্ছে...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Update Modal -->
    <div x-show="showPasswordModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showPasswordModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showPasswordModal = false"></div>
            
            <div x-show="showPasswordModal" x-transition class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    পাসওয়ার্ড পরিবর্তন
                </h3>
                
                <form wire:submit="updatePassword">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">বর্তমান পাসওয়ার্ড</label>
                            <input type="password" wire:model="current_password" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-violet-500 focus:ring-violet-500">
                            @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">নতুন পাসওয়ার্ড</label>
                            <input type="password" wire:model="new_password" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-violet-500 focus:ring-violet-500">
                            @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" wire:model="new_password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showPasswordModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            বাতিল
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-violet-600 rounded-lg hover:bg-violet-700 flex items-center">
                            <span wire:loading.remove wire:target="updatePassword">আপডেট করুন</span>
                            <span wire:loading wire:target="updatePassword">আপডেট হচ্ছে...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
