<div x-data="{ showGeneralModal: false, showPasswordModal: false }" 
     @close-modal.window="if($event.detail.id === 'general-info-modal') showGeneralModal = false; if($event.detail.id === 'password-modal') showPasswordModal = false;">
    
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">আমার প্রোফাইল</h2>
            <p class="text-sm text-gray-500">শিক্ষার্থীর ব্যক্তিগত এবং একাডেমিক তথ্য</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="showGeneralModal = true" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                তথ্য আপডেট
            </button>
            <button @click="showPasswordModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                পাসওয়ার্ড পরিবর্তন
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <x-shared.profile-header
            color="emerald"
            :photoPath="$student->photo_path"
            :fallbackInitial="mb_substr($student->name, 0, 1)"
            :title="$student->name"
            :subtitle="'আইডি: ' . $student->admission_no"
        >
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">শ্রেণী: {{ $student->schoolClass->name ?? 'N/A' }}</span>
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">শাখা: {{ $student->section->name ?? 'N/A' }}</span>
            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">রোল: {{ $student->roll_no }}</span>
        </x-shared.profile-header>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Info -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        ব্যক্তিগত তথ্য
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">জন্ম তারিখ</dt>
                            <dd class="text-sm font-semibold text-gray-800">
                                {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d F Y') : '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">লিঙ্গ</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->gender?->label() ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">ধর্ম</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->religion?->label() ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">রক্তের গ্রুপ</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->blood_group?->label() ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">ভর্তির তারিখ</dt>
                            <dd class="text-sm font-semibold text-gray-800">
                                {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d F Y') : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Guardian Info -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        অভিভাবকের তথ্য
                    </h4>
                    <dl class="space-y-4">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">পিতার নাম</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->father_name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">মাতার নাম</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->mother_name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500">অভিভাবকের মোবাইল</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->guardian_phone ?? '-' }}</dd>
                        </div>
                        <div class="border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500 mb-1">বর্তমান ঠিকানা</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->present_address ?? '-' }}</dd>
                        </div>
                        <div class="border-b border-gray-50 pb-2">
                            <dt class="text-sm text-gray-500 mb-1">স্থায়ী ঠিকানা</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $student->permanent_address ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- General Info Update Modal -->
    <div x-show="showGeneralModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showGeneralModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showGeneralModal = false"></div>
            
            <div x-show="showGeneralModal" x-transition class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    সাধারণ তথ্য আপডেট
                </h3>
                <form wire:submit="updateGeneralInfo">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">রক্তের গ্রুপ</label>
                            <select wire:model="blood_group" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">জানা নেই</option>
                                @foreach (\App\Enums\BloodGroup::cases() as $bgEnum)
                                    <option value="{{ $bgEnum->value }}">{{ $bgEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('blood_group') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষার্থীর মোবাইল</label>
                            <input type="text" wire:model="student_phone" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('student_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">বর্তমান ঠিকানা</label>
                            <textarea wire:model="address_present" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" rows="3"></textarea>
                            @error('address_present') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showGeneralModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none">বাতিল</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md hover:bg-emerald-700 focus:outline-none">
                            <span wire:loading.remove wire:target="updateGeneralInfo">সেভ করুন</span>
                            <span wire:loading wire:target="updateGeneralInfo">অপেক্ষা করুন...</span>
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
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    পাসওয়ার্ড পরিবর্তন
                </h3>
                <form wire:submit="updatePassword">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">বর্তমান পাসওয়ার্ড</label>
                            <input type="password" wire:model="current_password" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">নতুন পাসওয়ার্ড</label>
                            <input type="password" wire:model="new_password" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" wire:model="new_password_confirmation" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showPasswordModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none">বাতিল</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md hover:bg-emerald-700 focus:outline-none">
                            <span wire:loading.remove wire:target="updatePassword">পরিবর্তন করুন</span>
                            <span wire:loading wire:target="updatePassword">অপেক্ষা করুন...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
