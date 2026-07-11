@section('header', 'শ্রেণী (Class) পরিচালনা')

<div class="space-y-6">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="flex justify-end">
        <button wire:click="create" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
            নতুন শ্রেণী যোগ করুন
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">শ্রেণী তালিকা</h3>
        </div>
        <div class="border-t border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">নাম</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ক্রম (Order)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শিক্ষাবর্ষ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($classes as $cls)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $cls->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cls->numeric_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cls->academicYear ? $cls->academicYear->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $cls->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">এডিট</button>
                                <button wire:click="delete({{ $cls->id }})" wire:confirm="আপনি কি নিশ্চিত?" class="text-red-600 hover:text-red-900">ডিলিট</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                কোনো শ্রেণী পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal using Alpine.js -->
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-10 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;">
         
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <form wire:submit="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $isEditing ? 'শ্রেণী আপডেট করুন' : 'নতুন শ্রেণী যোগ করুন' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">শ্রেণীর নাম</label>
                                        <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="numeric_order" class="block text-sm font-medium text-gray-700">ক্রম (Numeric Order)</label>
                                        <input type="number" wire:model="numeric_order" id="numeric_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @error('numeric_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700">শিক্ষাবর্ষ</label>
                                        <select wire:model="academic_year_id" id="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">-- শিক্ষাবর্ষ নির্বাচন করুন --</option>
                                            @foreach($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('academic_year_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            সংরক্ষণ করুন
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            বাতিল
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
