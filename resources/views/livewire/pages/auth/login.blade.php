<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        if ($user->hasRole('student')) {
            $this->redirectIntended(default: '/student/dashboard', navigate: true);
        } else {
            $this->redirectIntended(default: '/admin/dashboard', navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email or Phone -->
        <div>
            <x-input-label for="login" :value="__('ইমেইল বা মোবাইল নাম্বার')" />
            <x-text-input wire:model="form.login" id="login" class="block mt-1 w-full" type="text" name="login" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('পাসওয়ার্ড')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('মনে রাখুন') }}</span>
            </label>
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" style="width: 25%; min-width: 120px;" class="theme-bg hover:bg-emerald-900 text-white font-bold py-2.5 rounded-xl shadow-md shadow-emerald-200 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 flex justify-center">
                লগইন
            </button>
        </div>

        <div class="text-center mt-4 mb-2">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('পাসওয়ার্ড ভুলে গেছেন?') }}
                </a>
            @endif
        </div>
    </form>
</div>
