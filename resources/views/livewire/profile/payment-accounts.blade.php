<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Payment Accounts
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Manage your payment accounts for receiving salaries or other payments.
        </p>
    </header>

    <div class="mt-6 space-y-4">
        @if (session()->has('message'))
            <div class="p-3 bg-green-50 text-green-700 rounded-lg text-sm border border-green-200">
                {{ session('message') }}
            </div>
        @endif

        @if(!$showForm)
            <div class="flex justify-end mb-4">
                <x-primary-button wire:click="create">Add New Account</x-primary-button>
            </div>

            @if($accounts->isEmpty())
                <div class="p-4 bg-gray-50 text-gray-500 rounded-lg text-center text-sm border border-gray-200">
                    No payment accounts added yet.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($accounts as $account)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg {{ $account->is_default ? 'bg-indigo-50 border-indigo-200' : 'bg-white' }}">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    {{ $account->paymentMethod->en_name }} - {{ $account->account_name }}
                                    @if($account->is_default)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Default
                                        </span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">A/C: {{ $account->account_number }}</p>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $account->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                                <button wire:click="delete({{ $account->id }})" onclick="confirm('Are you sure you want to delete this account?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <form wire:submit="save" class="space-y-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div>
                    <x-input-label for="payment_method_id" value="Payment Method" />
                    <select wire:model="payment_method_id" id="payment_method_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm">
                        <option value="">Select Method</option>
                        @foreach($availableMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->bn_name ?? $method->en_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('payment_method_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="account_name" value="Account Name" />
                    <x-text-input wire:model="account_name" id="account_name" type="text" class="mt-1 block w-full" placeholder="e.g. John Doe" />
                    <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="account_number" value="Account Number / Mobile No" />
                    <x-text-input wire:model="account_number" id="account_number" type="text" class="mt-1 block w-full" placeholder="e.g. 017xxxxxxxx" />
                    <x-input-error :messages="$errors->get('account_number')" class="mt-2" />
                </div>

                <div class="block">
                    <label for="is_default" class="inline-flex items-center">
                        <input id="is_default" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" wire:model="is_default">
                        <span class="ms-2 text-sm text-gray-600">Set as default account</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 mt-4">
                    <button type="button" wire:click="resetForm" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Cancel</button>
                    <x-primary-button>
                        {{ __('Save Account') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
    </div>
</section>
