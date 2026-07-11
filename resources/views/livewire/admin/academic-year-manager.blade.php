@section('header', 'শিক্ষাবর্ষ (Academic Year) পরিচালনা')

<div class="space-y-6">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">
            {{ $isEditing ? 'শিক্ষাবর্ষ আপডেট করুন' : 'নতুন শিক্ষাবর্ষ যোগ করুন' }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">শিক্ষাবর্ষের নাম</label>
                    <input type="text" id="name" wire:model="name" placeholder="যেমন: ২০২৫-২০২৬" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">শুরুর তারিখ</label>
                    <input type="date" id="start_date" wire:model="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">শেষের তারিখ</label>
                    <input type="date" id="end_date" wire:model="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Is Current -->
                <div class="flex items-center mt-6">
                    <input id="is_current" type="checkbox" wire:model="is_current" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_current" class="ml-2 block text-sm text-gray-900">
                        বর্তমান শিক্ষাবর্ষ?
                    </label>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $isEditing ? 'আপডেট করুন' : 'যোগ করুন' }}
                </button>

                @if($isEditing)
                    <button type="button" wire:click="resetForm" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        বাতিল করুন
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">শিক্ষাবর্ষ তালিকা</h3>
        </div>
        <div class="border-t border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">নাম</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শুরুর তারিখ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শেষের তারিখ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">স্ট্যাটাস</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($years as $year)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $year->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $year->start_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $year->end_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($year->is_current)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        বর্তমান
                                    </span>
                                @else
                                    <button wire:click="toggleCurrent({{ $year->id }})" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800 transition">
                                        সেট বর্তমান
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $year->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">এডিট</button>
                                <button wire:click="delete({{ $year->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-600 hover:text-red-900">ডিলিট</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                কোনো শিক্ষাবর্ষ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
