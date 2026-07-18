<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">ছুটির আবেদনসমূহ (Leave Applications)</h2>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm border-b">
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">আবেদনকারী</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">ধরণ</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">তারিখ</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">মোট দিন</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider whitespace-nowrap">স্ট্যাটাস</th>
                        <th class="py-3 px-4 font-semibold uppercase tracking-wider text-right whitespace-nowrap">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm bg-white">
                    @forelse($applications as $app)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 capitalize">{{ $app->employee->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $app->employee_type }} (ID: {{ $app->employee_id }})</div>
                            </td>
                            <td class="py-3 px-4 text-gray-500 whitespace-nowrap">{{ $app->leaveType->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($app->from_date)->format('d M') }} - 
                                {{ \Carbon\Carbon::parse($app->to_date)->format('d M, Y') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-700">{{ $app->total_days }}</span> দিন
                                @if($app->is_half_day)
                                    <span class="ml-1 px-2 py-0.5 text-[10px] font-medium rounded-full bg-orange-100 text-orange-800">Half Day</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($app->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($app->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Approved</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 capitalize">{{ $app->status }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                @if($app->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="openReviewModal({{ $app->id }}, 'approved')" class="px-3 py-1.5 text-xs font-medium text-white bg-emerald-500 hover:bg-emerald-600 rounded-lg shadow-sm transition">Approve</button>
                                        <button wire:click="openReviewModal({{ $app->id }}, 'rejected')" class="px-3 py-1.5 text-xs font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-sm transition">Reject</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">কোনো আবেদন পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 capitalize">
                {{ $actionType === 'approved' ? 'Approve Application' : 'Reject Application' }}
            </h3>
            <form wire:submit.prevent="submitReview">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">রিভিউ নোট (ঐচ্ছিক)</label>
                    <textarea wire:model="reviewNote" rows="3" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeReviewModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        বাতিল
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white {{ $actionType === 'approved' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700' }} rounded-lg">
                        নিশ্চিত করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
