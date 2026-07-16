<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">এসএমএস পাঠান (Send SMS)</h2>
            <p class="text-sm text-gray-500">Send bulk SMS messages to teachers, students, or custom numbers.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Target Selection -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Select Audience</h3>
                
                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="target_teachers" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5">
                        <span class="ml-3 text-sm font-medium text-gray-700">All Teachers</span>
                    </label>
                    
                    <div class="border-t pt-4">
                        <label class="flex items-center mb-3">
                            <input type="checkbox" wire:model.live="target_students" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5">
                            <span class="ml-3 text-sm font-medium text-gray-700">Students</span>
                        </label>

                        @if($target_students)
                            <div class="pl-8 space-y-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Specific Class</label>
                                    <select wire:model.live="selected_class_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        <option value="">All Classes</option>
                                        @foreach($this->classes as $cls)
                                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($selected_class_id)
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Specific Section</label>
                                        <select wire:model="selected_section_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            <option value="">All Sections</option>
                                            @foreach($this->sections as $sec)
                                                <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Custom Numbers (Comma separated)</label>
                        <input type="text" wire:model="custom_numbers" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" placeholder="e.g. 01711000000, 01811000000">
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Composer -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Compose Message</h3>
                
                <form wire:submit="sendSms">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message Content</label>
                        <textarea wire:model="message" rows="6" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required placeholder="Type your SMS message here..."></textarea>
                        <div class="mt-2 flex justify-between text-xs text-gray-500">
                            <span>Keep it short. Max 160 characters per SMS.</span>
                            <span>{{ strlen($message) }} characters</span>
                        </div>
                        @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg flex items-center transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Send SMS Broadcast
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
