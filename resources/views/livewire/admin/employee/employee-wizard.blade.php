<div>
    <div class="max-w-4xl mx-auto py-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach([1 => 'ভূমিকা ও দাপ্তরিক', 2 => 'ব্যক্তিগত তথ্য', 3 => 'শিক্ষাগত যোগ্যতা', 4 => 'বেতন ও ভাতা'] as $step => $label)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep == $step ? 'bg-indigo-600 text-white shadow-lg' : ($currentStep > $step ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500') }} transition-all duration-300">
                            @if($currentStep > $step)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        <span class="mt-2 text-sm font-medium {{ $currentStep >= $step ? 'text-gray-800' : 'text-gray-400' }}">{{ $label }}</span>
                    </div>
                    @if($step < 4)
                        <div class="flex-1 h-1 mx-4 rounded-full {{ $currentStep > $step ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form wire:submit.prevent="submit">
                
                <div class="p-8">
                    <!-- Step 1: Role & Official Info -->
                    @if($currentStep == 1)
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">ভূমিকা ও দাপ্তরিক তথ্য</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-full bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">ভূমিকা নির্বাচন করুন (Select Role) <span class="text-red-500">*</span></label>
                                    <div class="flex space-x-6">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" wire:model.live="role" value="teacher" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="text-gray-700 font-medium text-lg">শিক্ষক (Teacher)</span>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" wire:model.live="role" value="staff" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="text-gray-700 font-medium text-lg">স্টাফ (Staff)</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">আইডি নাম্বার (Employee ID) <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('employee_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">পদবি (Designation) <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="designation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('designation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">চাকরির ধরন (Employment Type) <span class="text-red-500">*</span></label>
                                    <select wire:model="contract_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1">স্থায়ী (Permanent)</option>
                                        <option value="2">চুক্তিভিত্তিক (Contractual)</option>
                                        <option value="3">খণ্ডকালীন (Part-time)</option>
                                    </select>
                                    @error('contract_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">যোগদানের তারিখ (Joining Date) <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="joining_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('joining_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                @if($role === 'teacher')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">বিশেষজ্ঞতা (Specialization) <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="specialization" placeholder="e.g. Mathematics, Quran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('specialization') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                @if($role === 'staff')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">বিভাগ (Department) <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="department" placeholder="e.g. Administration, Cleaning" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('department') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">অবস্থা (Status) <span class="text-red-500">*</span></label>
                                    <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1">সক্রিয় (Active)</option>
                                        <option value="0">নিষ্ক্রিয় (Inactive)</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 2: Personal Info -->
                    @if($currentStep == 2)
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">ব্যক্তিগত তথ্য</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">নাম (Name) <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">মোবাইল নাম্বার (Phone) <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="phone" placeholder="01XXXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="text-xs text-gray-500">এই নাম্বারটি লগইন পাসওয়ার্ড হিসেবে ব্যবহৃত হবে।</span>
                                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ইমেইল (Email)</label>
                                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">জাতীয় পরিচয়পত্র (NID) <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nid" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('nid') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-full">
                                    <label class="block text-sm font-medium text-gray-700">ছবি (Photo)</label>
                                    <div class="mt-1 flex items-center">
                                        <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </div>
                                    @if ($photo)
                                        <div class="mt-2">
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border shadow-sm">
                                        </div>
                                    @endif
                                    @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 3: Academic Qualification -->
                    @if($currentStep == 3)
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-3">
                                <h2 class="text-2xl font-bold text-gray-800">শিক্ষাগত যোগ্যতা</h2>
                                <button type="button" wire:click="addQualification" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-semibold hover:bg-emerald-200 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    আরও যোগ করুন
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($qualifications as $index => $qual)
                                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 relative">
                                        @if(count($qualifications) > 1)
                                            <button type="button" wire:click="removeQualification({{ $index }})" class="absolute top-3 right-3 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1 rounded-full transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        @endif
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">ডিগ্রির নাম (Degree Name) <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="qualifications.{{ $index }}.degree_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @error('qualifications.'.$index.'.degree_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">প্রতিষ্ঠান (Institution) <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="qualifications.{{ $index }}.institution" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @error('qualifications.'.$index.'.institution') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">পাসের সাল (Passing Year) <span class="text-red-500">*</span></label>
                                                <input type="number" wire:model="qualifications.{{ $index }}.passing_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @error('qualifications.'.$index.'.passing_year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">ফলাফল (Result) <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="qualifications.{{ $index }}.result" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @error('qualifications.'.$index.'.result') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Step 4: Salary & Compensation -->
                    @if($currentStep == 4)
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">বেতন ও ভাতা (Salary & Compensation)</h2>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">মূল বেতন (Basic Salary) <span class="text-red-500">*</span></label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">৳</span>
                                    </div>
                                    <input type="number" wire:model="basic_salary" class="pl-8 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00">
                                </div>
                                @error('basic_salary') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                                <!-- Bank Info -->
                                <div class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100">
                                    <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        ব্যাংক একাউন্ট (Bank Account)
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">ব্যাংকের নাম (Bank Name)</label>
                                            <input type="text" wire:model="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('bank_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">একাউন্টের নাম (A/C Name)</label>
                                            <input type="text" wire:model="bank_ac_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('bank_ac_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">একাউন্ট নং (A/C No)</label>
                                            <input type="text" wire:model="bank_ac_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('bank_ac_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">রাউটিং নং (Routing No) - ঐচ্ছিক</label>
                                            <input type="text" wire:model="bank_routing_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('bank_routing_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- MFS Info -->
                                <div class="bg-pink-50/50 p-5 rounded-xl border border-pink-100">
                                    <h3 class="text-lg font-semibold text-pink-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        মোবাইল ব্যাংকিং (MFS)
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">এমএফএস এর নাম (MFS Name)</label>
                                            <select wire:model="mfs_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">নির্বাচন করুন (Select)</option>
                                                <option value="bKash">বিকাশ (bKash)</option>
                                                <option value="Nagad">নগদ (Nagad)</option>
                                                <option value="Rocket">রকেট (Rocket)</option>
                                            </select>
                                            @error('mfs_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">একাউন্ট নং (MFS A/C No)</label>
                                            <input type="text" wire:model="mfs_ac_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('mfs_ac_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Buttons -->
                <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        @if($currentStep > 1)
                            <button type="button" wire:click="prevStep" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition shadow-sm">
                                পিছনে যান
                            </button>
                        @endif
                    </div>
                    <div>
                        @if($currentStep < 4)
                            <button type="button" wire:click="nextStep" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                পরবর্তী ধাপ
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        @else
                            <button type="submit" class="px-8 py-2.5 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-sm shadow-emerald-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                নিয়োগ সম্পন্ন করুন
                            </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
