{{-- Public Multi-step Admission Form --}}
<div class="space-y-6" x-data="{}">

    {{-- ── Success Screen ─────────────────────────────────────────────── --}}
    @if($submitted)
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center space-y-5 border border-green-100">
            <div class="flex justify-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">আবেদন সফলভাবে জমা হয়েছে!</h2>
                <p class="text-gray-500 mt-2">আপনার আবেদন নম্বর:</p>
                <p class="text-3xl font-bold text-indigo-600 tracking-wider mt-1">{{ $applicationNo }}</p>
            </div>
            <div class="bg-indigo-50 rounded-xl p-4 text-sm text-indigo-800">
                <p>আবেদনটি পর্যালোচনা করার পর আমরা আপনার দেওয়া ফোন নম্বরে SMS পাঠাব।</p>
                <p class="mt-1 font-medium">আবেদন নম্বরটি সংরক্ষণ করুন।</p>
            </div>
            <a href="{{ url('/admission') }}" class="inline-block px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                নতুন আবেদন করুন
            </a>
        </div>

    @else

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="text-center space-y-1">
        <h1 class="text-2xl font-bold text-gray-900">ভর্তি আবেদন ফর্ম</h1>
        <p class="text-gray-500 text-sm">সকল তথ্য সঠিকভাবে পূরণ করুন</p>
    </div>

    {{-- ── Step Indicator ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
        <div class="flex items-center justify-between relative">
            <!-- Progress Line -->
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 z-0 mx-10"></div>
            <div class="absolute top-5 left-0 h-0.5 bg-indigo-500 z-0 mx-10 transition-all duration-500"
                 style="width: calc({{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}% - 2.5rem)"></div>

            @php
                $steps = [
                    1 => ['label' => 'শিক্ষার্থী', 'icon' => '👤'],
                    2 => ['label' => 'অভিভাবক', 'icon' => '👨‍👩‍👧'],
                    3 => ['label' => 'শিক্ষাগত', 'icon' => '📚'],
                    4 => ['label' => 'পর্যালোচনা', 'icon' => '✅'],
                ];
            @endphp

            @foreach($steps as $step => $info)
                <div class="flex flex-col items-center z-10">
                    <div @class([
                        'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300',
                        'bg-indigo-600 text-white shadow-lg shadow-indigo-200' => $currentStep >= $step,
                        'bg-gray-100 text-gray-400' => $currentStep < $step,
                    ])>
                        @if($currentStep > $step)
                            ✓
                        @else
                            {{ $step }}
                        @endif
                    </div>
                    <span @class([
                        'text-xs mt-2 font-medium',
                        'text-indigo-600' => $currentStep >= $step,
                        'text-gray-400'   => $currentStep < $step,
                    ])>{{ $info['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Form Card ────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8" wire:key="step-{{ $currentStep }}">

        {{-- ════ STEP 1: Student Info ════ --}}
        @if($currentStep === 1)
            <div class="space-y-5">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="text-2xl">👤</span>
                    <h2 class="text-lg font-bold text-gray-800">শিক্ষার্থীর তথ্য</h2>
                </div>

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        শিক্ষার্থীর পুরো নাম <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="applicant_name"
                           id="applicant_name"
                           placeholder="শিক্ষার্থীর নাম লিখুন"
                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                    @error('applicant_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- DOB --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">জন্ম তারিখ</label>
                        <input type="date"
                               wire:model="dob"
                               id="dob"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                        @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">লিঙ্গ</label>
                        <select wire:model="gender" id="gender"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                            <option value="">-- নির্বাচন করুন --</option>
                            <option value="1">ছেলে</option>
                            <option value="2">মেয়ে</option>
                            <option value="3">অন্যান্য</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Photo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ছবি (সর্বোচ্চ ২ MB)</label>
                    <div class="flex items-center gap-4">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-cover border-2 border-indigo-200">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-3xl text-gray-300">📷</div>
                        @endif
                        <div class="flex-1">
                            <input type="file" wire:model="photo" id="photo" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <div wire:loading wire:target="photo" class="text-xs text-indigo-500 mt-1">আপলোড হচ্ছে...</div>
                        </div>
                    </div>
                    @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

        {{-- ════ STEP 2: Guardian Info ════ --}}
        @elseif($currentStep === 2)
            <div class="space-y-5">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="text-2xl">👨‍👩‍👧</span>
                    <h2 class="text-lg font-bold text-gray-800">অভিভাবকের তথ্য</h2>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">অভিভাবকের নাম (পিতা/মাতা)</label>
                    <input type="text" wire:model="guardian_name" id="guardian_name"
                           placeholder="পিতার নাম লিখুন"
                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                    @error('guardian_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">মোবাইল নম্বর</label>
                        <input type="tel" wire:model="guardian_phone" id="guardian_phone"
                               placeholder="01XXXXXXXXX"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                        @error('guardian_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">ইমেইল (ঐচ্ছিক)</label>
                        <input type="email" wire:model="guardian_email" id="guardian_email"
                               placeholder="example@gmail.com"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                        @error('guardian_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">বর্তমান ঠিকানা</label>
                    <textarea wire:model="address" id="address" rows="3"
                              placeholder="গ্রাম/এলাকা, উপজেলা, জেলা"
                              class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition resize-none"></textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

        {{-- ════ STEP 3: Academic Info ════ --}}
        @elseif($currentStep === 3)
            <div class="space-y-5">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="text-2xl">📚</span>
                    <h2 class="text-lg font-bold text-gray-800">শিক্ষাগত তথ্য</h2>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">পূর্ববর্তী বিদ্যালয়ের নাম</label>
                    <input type="text" wire:model="previous_school" id="previous_school"
                           placeholder="পূর্ববর্তী স্কুলের নাম"
                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                    @error('previous_school') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">ভর্তি ইচ্ছুক শ্রেণী</label>
                        <select wire:model="applying_for_class_id" id="applying_for_class_id"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                            <option value="">-- শ্রেণী নির্বাচন করুন --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('applying_for_class_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">শিক্ষাবর্ষ</label>
                        <select wire:model="academic_year_id" id="academic_year_id"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-indigo-200 text-sm py-3 px-4 transition">
                            <option value="">-- শিক্ষাবর্ষ নির্বাচন করুন --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                        @error('academic_year_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        {{-- ════ STEP 4: Review ════ --}}
        @elseif($currentStep === 4)
            <div class="space-y-5">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="text-2xl">✅</span>
                    <h2 class="text-lg font-bold text-gray-800">তথ্য পর্যালোচনা করুন</h2>
                </div>

                <div class="bg-indigo-50 rounded-xl p-4 text-sm text-indigo-800">
                    <p>নিচের তথ্যগুলো যাচাই করুন। কোনো তথ্য ভুল হলে "পূর্ববর্তী" বাটনে ক্লিক করে সংশোধন করুন।</p>
                </div>

                @php
                    $genderMap = [1 => 'ছেলে', 2 => 'মেয়ে', 3 => 'অন্যান্য'];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-xl bg-gray-50 p-4 space-y-3">
                        <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200 pb-2">শিক্ষার্থীর তথ্য</h3>
                        <p><span class="text-gray-500">নাম:</span> <strong>{{ $applicant_name ?: '—' }}</strong></p>
                        <p><span class="text-gray-500">জন্ম তারিখ:</span> <strong>{{ $dob ?: '—' }}</strong></p>
                        <p><span class="text-gray-500">লিঙ্গ:</span> <strong>{{ $genderMap[$gender] ?? '—' }}</strong></p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4 space-y-3">
                        <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200 pb-2">অভিভাবকের তথ্য</h3>
                        <p><span class="text-gray-500">নাম:</span> <strong>{{ $guardian_name ?: '—' }}</strong></p>
                        <p><span class="text-gray-500">ফোন:</span> <strong>{{ $guardian_phone ?: '—' }}</strong></p>
                        <p><span class="text-gray-500">ইমেইল:</span> <strong>{{ $guardian_email ?: '—' }}</strong></p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4 space-y-3 sm:col-span-2">
                        <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200 pb-2">শিক্ষাগত তথ্য</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <p><span class="text-gray-500">পূর্ববর্তী বিদ্যালয়:</span> <strong>{{ $previous_school ?: '—' }}</strong></p>
                            <p><span class="text-gray-500">ভর্তি ইচ্ছুক শ্রেণী:</span>
                                <strong>{{ $applying_for_class_id ? ($classes->firstWhere('id', $applying_for_class_id)?->name ?? '—') : '—' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Navigation Buttons ───────────────────────────────────────── --}}
        <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
            <button type="button" wire:click="prevStep"
                    @class(['px-5 py-2.5 text-sm font-medium rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition', 'invisible' => $currentStep === 1])>
                ← পূর্ববর্তী
            </button>

            @if($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                        class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                    <span wire:loading.remove wire:target="nextStep">পরবর্তী →</span>
                    <span wire:loading wire:target="nextStep">অপেক্ষা করুন...</span>
                </button>
            @else
                <button type="button" wire:click="submit"
                        class="px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition shadow-sm shadow-green-200">
                    <span wire:loading.remove wire:target="submit">✓ আবেদন জমা দিন</span>
                    <span wire:loading wire:target="submit">জমা হচ্ছে...</span>
                </button>
            @endif
        </div>
    </div>

    @endif
</div>
