@section('header', 'শিক্ষার্থীর তথ্য আপডেট')

<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            {{ $student->user->name ?? 'শিক্ষার্থী' }} - {{ $student->admission_no }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Class -->
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">শ্রেণী</label>
                    <select wire:model.live="class_id" id="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- নির্বাচন করুন --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('class_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Section -->
                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700">শাখা (ঐচ্ছিক)</label>
                    <select wire:model="section_id" id="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @disabled(!$class_id)>
                        <option value="">-- নির্বাচন করুন --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('section_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Roll Number -->
                <div>
                    <label for="roll_no" class="block text-sm font-medium text-gray-700">রোল নম্বর (ঐচ্ছিক)</label>
                    <input type="text" wire:model="roll_no" id="roll_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('roll_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">স্ট্যাটাস</label>
                    <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="1">সক্রিয়</option>
                        <option value="0">নিষ্ক্রিয়</option>
                        <option value="2">পাশ</option>
                        <option value="3">বহিষ্কৃত</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.students') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    বাতিল
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none transition">
                    সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
