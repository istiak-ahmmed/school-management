<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">নতুন শিক্ষার্থী ভর্তি</h2>
            <p class="text-sm text-gray-500 mt-1">শিক্ষার্থীর তথ্য দিয়ে নতুন প্রোফাইল তৈরি করুন</p>
        </div>
        <a href="{{ route('admin.students') }}"
            class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1 transition"
            wire:navigate>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            শিক্ষার্থী তালিকায় ফিরে যান
        </a>
    </div>

    <!-- Error/Success Messages -->
    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Progress Bar -->
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 z-0 rounded-full"></div>

                <!-- Step 1 Indicator -->
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 {{ $currentStep >= 1 ? 'bg-indigo-600 text-white border-4 border-indigo-100' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                        ১
                    </div>
                    <span
                        class="text-xs font-medium mt-2 {{ $currentStep >= 1 ? 'text-indigo-700' : 'text-gray-400' }}">একাডেমিক
                        তথ্য</span>
                </div>

                <!-- Step 2 Indicator -->
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 {{ $currentStep >= 2 ? 'bg-indigo-600 text-white border-4 border-indigo-100' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                        ২
                    </div>
                    <span
                        class="text-xs font-medium mt-2 {{ $currentStep >= 2 ? 'text-indigo-700' : 'text-gray-400' }}">ব্যক্তিগত
                        তথ্য</span>
                </div>

                <!-- Step 3 Indicator -->
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 {{ $currentStep >= 3 ? 'bg-indigo-600 text-white border-4 border-indigo-100' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                        ৩
                    </div>
                    <span
                        class="text-xs font-medium mt-2 {{ $currentStep >= 3 ? 'text-indigo-700' : 'text-gray-400' }}">অভিভাবকের
                        তথ্য</span>
                </div>
            </div>

            <div class="absolute left-0 top-[26px] h-1 bg-indigo-600 z-0 rounded-full transition-all duration-300 mx-10"
                style="width: {{ ($currentStep - 1) * 50 }}%; max-width: calc(100% - 80px);"></div>
        </div>

        <div class="p-6 sm:p-8">
            <form wire:submit.prevent="submit">

                {{-- ── Step 1: Academic Info ── --}}
                <div class="{{ $currentStep == 1 ? 'block' : 'hidden' }}">
                    <h3 class="text-lg font-bold text-gray-800 mb-5 border-b pb-2">১. একাডেমিক তথ্য</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Academic Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষাবর্ষ <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="academic_year_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">নির্বাচন করুন</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Class -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="class_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">নির্বাচন করুন</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শাখা <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="section_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                {{ empty($sections) ? 'disabled' : '' }}>
                                <option value="">নির্বাচন করুন</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Admission No -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ভর্তি নং <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="admission_no" readonly
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none text-gray-600 font-mono">
                            <span class="text-[10px] text-gray-400">স্বয়ংক্রিয়ভাবে তৈরি করা হয়েছে</span>
                            @error('admission_no')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Roll No -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">রোল নং <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="roll_no"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <span class="text-[10px] text-gray-400">পরামর্শকৃত রোল নম্বর। আপনি এটি পরিবর্তন করতে
                                পারেন।</span>
                            @error('roll_no')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Step 2: Personal Info ── --}}
                <div class="{{ $currentStep == 2 ? 'block' : 'hidden' }}">
                    <h3 class="text-lg font-bold text-gray-800 mb-5 border-b pb-2">২. শিক্ষার্থীর ব্যক্তিগত তথ্য</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষার্থীর নাম (পুরো নাম) <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="name"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="যেমন: মো: আব্দুল্লাহ">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">জন্ম তারিখ</label>
                            <input type="date" wire:model="date_birth"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('date_birth')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">লিঙ্গ <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="gender"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach (\App\Enums\Gender::cases() as $genderEnum)
                                    <option value="{{ $genderEnum->value }}">{{ $genderEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Blood Group -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">রক্তের গ্রুপ</label>
                            <select wire:model="blood_group"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">জানা নেই</option>
                                @foreach (\App\Enums\BloodGroup::cases() as $bgEnum)
                                    <option value="{{ $bgEnum->value }}">{{ $bgEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Religion -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ধর্ম <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="religion"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach (\App\Enums\Religion::cases() as $religionEnum)
                                    <option value="{{ $religionEnum->value }}">{{ $religionEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('religion')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Student Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষার্থীর মোবাইল নম্বর (যদি
                                থাকে)</label>
                            <input type="text" wire:model="student_phone"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="01XXXXXXXXX">
                            @error('student_phone')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Birth Certificate No -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">জন্ম নিবন্ধন নং</label>
                            <input type="text" wire:model="birth_certificate_no"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="জন্ম নিবন্ধন নম্বর লিখুন">
                            @error('birth_certificate_no')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Present Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">বর্তমান ঠিকানা <span
                                    class="text-red-500">*</span></label>
                            <textarea wire:model="address_present" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="গ্রাম/মহল্লা, ডাকঘর, থানা, জেলা"></textarea>
                            @error('address_present')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Permanent Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">স্থায়ী ঠিকানা</label>
                            <textarea wire:model="address_permanent" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="ফাঁকা রাখলে বর্তমান ঠিকানাই স্থায়ী ঠিকানা হিসেবে সংরক্ষিত হবে"></textarea>
                            @error('address_permanent')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Medical Info -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">মেডিকেল তথ্য (এলার্জি বা
                                অন্যান্য সমস্যা)</label>
                            <textarea wire:model="medical_info" rows="1"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="জানা থাকলে লিখুন"></textarea>
                            @error('medical_info')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Step 3: Guardian Info ── --}}
                <div class="{{ $currentStep == 3 ? 'block' : 'hidden' }}">
                    <h3 class="text-lg font-bold text-gray-800 mb-5 border-b pb-2">৩. অভিভাবকের তথ্য</h3>

                    <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg mb-6 border border-yellow-100 text-sm">
                        <strong>নোট:</strong> অভিভাবকের মোবাইল নম্বরটি অত্যন্ত গুরুত্বপূর্ণ। এই নম্বরটি দিয়ে একই
                        অভিভাবকের একাধিক সন্তান যুক্ত করা সম্ভব।
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Guardian Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">প্রধান অভিভাবকের নাম <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="guardian_name"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="পিতা/মাতা বা অভিভাবকের নাম">
                            @error('guardian_name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Guardian Relation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">শিক্ষার্থীর সাথে সম্পর্ক <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="guardian_relation"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="father">পিতা</option>
                                <option value="mother">মাতা</option>
                                <option value="guardian">অন্যান্য অভিভাবক</option>
                            </select>
                            @error('guardian_relation')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Guardian Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">মোবাইল নম্বর <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="guardian_phone"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="01XXXXXXXXX">
                            @error('guardian_phone')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Guardian Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ইমেইল (ঐচ্ছিক)</label>
                            <input type="email" wire:model="guardian_email"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="example@email.com">
                            @error('guardian_email')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Guardian Occupation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">প্রধান অভিভাবকের পেশা</label>
                            <input type="text" wire:model="guardian_occupation"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="যেমন: ব্যবসা / চাকরি">
                            @error('guardian_occupation')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mother Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">মাতার নাম (যদি প্রধান অভিভাবক
                                না হন)</label>
                            <input type="text" wire:model="mother_name"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @error('mother_name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Navigation Buttons ── --}}
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        @if ($currentStep > 1)
                            <button type="button" wire:click="previousStep"
                                class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                পূর্ববর্তী ধাপ
                            </button>
                        @endif
                    </div>

                    <div>
                        @if ($currentStep < $totalSteps)
                            <button type="button" wire:click="nextStep"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition flex items-center gap-2 shadow-sm">
                                পরবর্তী ধাপ
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition flex items-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                ভর্তি সম্পূর্ণ করুন
                            </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
