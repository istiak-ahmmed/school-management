<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">নোটিশ বোর্ড (Notice Board)</h2>
            <p class="text-sm text-gray-500">Manage public and targeted notices for the institution.</p>
        </div>
        <button wire:click="$toggle('showForm')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ $showForm ? 'বাতিল করুন' : 'নতুন নোটিশ' }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    @if($showForm)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Create New Notice</h3>
            <form wire:submit="saveNotice" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="category" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="general">General</option>
                            <option value="exam">Exam</option>
                            <option value="holiday">Holiday</option>
                            <option value="fee">Fee</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Attachment (Optional)</label>
                        <input type="file" wire:model="attachment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('attachment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea wire:model="body" rows="4" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required></textarea>
                    @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Targeting Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" wire:model.live="is_targeted" id="is_targeted" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5">
                        <label for="is_targeted" class="ml-2 block text-sm font-medium text-gray-900">Target Specific Audience (Sends SMS/Email)</label>
                    </div>

                    @if($is_targeted)
                        <div class="space-y-4 pl-7">
                            <p class="text-sm text-gray-600">Select who should receive this notice directly. If none selected, it is broadcast to everyone if channels are enabled.</p>
                            
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="target_teachers" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">All Teachers</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.live="target_students" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Students</span>
                                </label>
                            </div>

                            @if($target_students)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <label class="block text-xs text-gray-500">Specific Class (Optional)</label>
                                        <select wire:model.live="selected_class_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            <option value="">All Classes</option>
                                            @foreach($this->classes as $cls)
                                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($selected_class_id)
                                        <div>
                                            <label class="block text-xs text-gray-500">Specific Section (Optional)</label>
                                            <select wire:model="selected_section_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                <option value="">All Sections in this Class</option>
                                                @foreach($this->sections as $sec)
                                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="border-t pt-3 mt-3">
                                <p class="text-sm font-medium text-gray-700 mb-2">Send Channels</p>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="send_email" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Send via Email</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="send_sms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Send via SMS</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 pl-7 mt-1">Leave unchecked to create a public notice visible to everyone on the website.</p>
                    @endif
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_pinned" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Pin Notice</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Publish Immediately</span>
                    </label>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition duration-150 ease-in-out">
                        Save Notice
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Notices List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">নোটিশের শিরোনাম</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ক্যাটাগরি</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">প্রাপক</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($notices as $notice)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($notice->is_pinned)
                                    <svg class="w-4 h-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                @endif
                                <div class="text-sm font-medium text-gray-900">{{ $notice->title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($notice->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if(empty($notice->audience))
                                <span class="text-emerald-600 font-medium">Public</span>
                            @else
                                {{ implode(', ', array_map('ucfirst', $notice->audience)) }}
                            @endif
                            @if($notice->is_sms_sent || $notice->is_email_sent)
                                <div class="text-xs text-gray-400 mt-1">
                                    Sent via: 
                                    @if($notice->is_sms_sent) SMS @endif
                                    @if($notice->is_sms_sent && $notice->is_email_sent) & @endif
                                    @if($notice->is_email_sent) Email @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $notice->created_at->format('d M, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="deleteNotice({{ $notice->id }})" wire:confirm="Are you sure you want to delete this notice?" class="text-red-600 hover:text-red-900 ml-3">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">কোনো নোটিশ পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notices->links() }}
        </div>
    </div>
</div>
