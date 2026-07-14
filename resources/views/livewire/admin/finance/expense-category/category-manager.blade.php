<div>
    @section('header', 'খরচের খাতসমূহ')

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">খাত তালিকা</h3>
            <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                নতুন খাত যোগ করুন
            </button>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 font-medium">নাম</th>
                    <th class="px-5 py-3 font-medium">বিবরণ</th>
                    <th class="px-5 py-3 font-medium">অবস্থা</th>
                    <th class="px-5 py-3 font-medium text-right">একশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-5 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $category->name }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $category->description }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $category->id }})" class="relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none {{ $category->is_active ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                <span class="inline-block w-4 h-4 transform bg-white rounded-full transition ease-in-out duration-200 {{ $category->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right font-medium">
                            <button wire:click="edit({{ $category->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">এডিট</button>
                            <button wire:click="delete({{ $category->id }})" class="text-red-500 hover:text-red-700" onclick="confirm('আপনি কি নিশ্চিত যে এই খাতটি মুছে ফেলতে চান?') || event.stopImmediatePropagation()">ডিলিট</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-4 text-center text-gray-500">কোনো খাত পাওয়া যায়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            {{ $categoryId ? 'খাত আপডেট করুন' : 'নতুন খাত যোগ করুন' }}
                        </h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">নাম <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">বিবরণ</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1">
                            <label class="ml-2 block text-sm text-gray-900">
                                সক্রিয় (Active)
                            </label>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-semibold text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            সংরক্ষণ করুন
                        </button>
                        <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-200 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            বাতিল
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
