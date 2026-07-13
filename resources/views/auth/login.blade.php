<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="ইমেইল (Email)" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="পাসওয়ার্ড (Password)" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">মনে রাখুন (Remember me)</span>
            </label>
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" style="width: 25%; min-width: 120px;" class="theme-bg hover:bg-emerald-900 text-white font-bold py-2.5 rounded-xl shadow-md shadow-emerald-200 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                লগইন
            </button>
        </div>

        <div class="text-center mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm theme-text hover:text-emerald-900 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition" href="{{ route('password.request') }}">
                    পাসওয়ার্ড ভুলে গেছেন?
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
