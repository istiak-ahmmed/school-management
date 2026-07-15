<div>
    @section('header', 'রোল ও পারমিশন (Roles & Permissions)')

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

    <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4 text-sm text-blue-800">
        <svg class="w-6 h-6 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <strong class="font-semibold block mb-1">রোল ও পারমিশন সম্পর্কে তথ্য:</strong>
            <ul class="list-disc ml-5 space-y-1 text-blue-700">
                <li><span class="font-semibold text-purple-800 bg-purple-100 px-1.5 py-0.5 rounded text-[10px]">সর্বোচ্চ ক্ষমতা</span> (Super Admin) - এদের সব ফিচারে স্বয়ংক্রিয়ভাবে অ্যাক্সেস থাকে। এদের পারমিশন এডিট করার প্রয়োজন নেই।</li>
                <li><span class="font-semibold text-blue-800 bg-blue-100 px-1.5 py-0.5 rounded text-[10px]">সিস্টেম ডিফল্ট</span> (Student, Teacher, Parent) - এদের পারমিশন মূলত কোডের মাধ্যমে নিয়ন্ত্রিত হয় (যেমন: শিক্ষক শুধু তার ক্লাস দেখবেন, শিক্ষার্থী শুধু তার রেজাল্ট)। তবে আপনি চাইলে এদেরকে অতিরিক্ত পারমিশন (যেমন: নোটিশ বোর্ড তৈরি করা) দিতে পারেন।</li>
                <li>বাকি সাধারণ রোলগুলোর (যেমন: Admin, Accountant) পারমিশন আপনি নিজের ইচ্ছামত তৈরি বা এডিট করতে পারবেন।</li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">রোল তালিকা</h3>
            <button wire:click="openModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                নতুন রোল তৈরি করুন
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="bg-gray-50 border-b border-gray-100 text-center text-xs text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 font-medium"  wire:click="sortBy('id')" >
    <div class="flex items-center justify-center space-x-1">
        <span>ক্র: নং</span>
        @if($sortField === 'id')
            @if($sortDirection === 'asc')
                <svg class="w-3 h-3 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            @else
                <svg class="w-3 h-3 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            @endif
        @else
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
        @endif
    </div>
</th>
                        <th class="px-5 py-3 font-medium">রোলের নাম</th>
                        <th class="px-5 py-3 font-medium">পারমিশন সংখ্যা</th>
                        <th class="px-5 py-3 font-medium text-right">একশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($roles as $role)
                        <tr>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-900 font-medium">
                                {{ Str::headline($role->name) }}
                                @if($role->name === 'super-admin')
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-100 text-purple-800">সর্বোচ্চ ক্ষমতা</span>
                                @elseif(in_array($role->name, ['student', 'teacher', 'parent']))
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800" title="এই রোলগুলোর পারমিশন সিস্টেম দ্বারা স্বয়ংক্রিয়ভাবে নিয়ন্ত্রিত হয়।">সিস্টেম ডিফল্ট</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                @if($role->name === 'super-admin')
                                    সব পারমিশন
                                @else
                                    {{ $role->permissions->count() }} টি পারমিশন
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-medium">
                                @if($role->name !== 'super-admin')
                                    <button wire:click="edit({{ $role->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">এডিট</button>
                                @endif
                                
                                @if(!in_array($role->name, ['super-admin', 'student', 'teacher', 'parent', 'public']))
                                    <button wire:click="delete({{ $role->id }})" class="text-red-500 hover:text-red-700" onclick="confirm('আপনি কি নিশ্চিত যে এই রোলটি মুছে ফেলতে চান?') || event.stopImmediatePropagation()">ডিলিট</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full">
                <form wire:submit.prevent="save">
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                            {{ $roleId ? 'রোল আপডেট করুন' : 'নতুন রোল তৈরি করুন' }}
                        </h3>
                        <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="bg-white px-6 pt-5 pb-6">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">রোলের নাম (e.g. assistant-accountant) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="block w-full md:w-1/2 rounded-lg border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <h4 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">পারমিশন নির্বাচন করুন (Select Permissions)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[50vh] overflow-y-auto pr-2">
                            @foreach($groupedPermissions as $module => $permissions)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <h5 class="font-medium text-gray-800 mb-3 capitalize text-sm">{{ str_replace('_', ' ', $module) }}</h5>
                                    <div class="space-y-2">
                                        @foreach($permissions as $perm)
                                            @php 
                                                $action = explode('.', $perm->name)[1] ?? $perm->name; 
                                            @endphp
                                            <div class="flex items-center">
                                                <input type="checkbox" id="perm_{{ $perm->id }}" wire:model="rolePermissions.{{ $perm->name }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                                                <label for="perm_{{ $perm->id }}" class="ml-2 block text-sm text-gray-700 cursor-pointer capitalize">
                                                    {{ $action }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                            সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
