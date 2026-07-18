<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">ছুটির ধরন (Leave Types)</h2>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">নাম</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">সর্বোচ্চ দিন (বছরে)</label>
                <input type="number" wire:model="max_days_per_year" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                @error('max_days_per_year') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">প্রযোজ্য</label>
                <select wire:model="applicable_to" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="both">উভয় (Both)</option>
                    <option value="teacher">শিক্ষক (Teacher)</option>
                    <option value="staff">স্টাফ (Staff)</option>
                </select>
                @error('applicable_to') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center h-10">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_paid" class="rounded text-emerald-600 focus:ring-emerald-500 mr-2">
                    <span class="text-sm font-medium text-gray-700">বেতনসহ (Paid)?</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-medium">
                    {{ $editId ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                </button>
                @if($editId)
                <button type="button" wire:click="resetFields" class="w-full mt-2 text-sm text-gray-500 hover:text-gray-700 underline">
                    বাতিল
                </button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm border-b">
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider">নাম</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider">দিন/বছর</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider">প্রযোজ্য</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider">পেইড</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm bg-white">
                    @forelse($leaveTypes as $type)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $type->name }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $type->max_days_per_year }}</td>
                            <td class="py-3 px-4 text-gray-500 capitalize">{{ $type->applicable_to }}</td>
                            <td class="py-3 px-4">
                                @if($type->is_paid)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">হ্যাঁ</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">না</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button wire:click="edit({{ $type->id }})" class="text-blue-600 hover:text-blue-800 mr-3">এডিট</button>
                                <button wire:click="delete({{ $type->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-600 hover:text-red-800">ডিলিট</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">কোনো ডেটা পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
