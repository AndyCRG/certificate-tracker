<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" style="color: rgb(127,98,44);" />
            <x-text-input id="email" class="block mt-1 w-full border rounded p-2"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" style="color: rgb(127,98,44);" />
            <x-text-input id="password" class="block mt-1 w-full border rounded p-2"
                type="password" name="password" required autocomplete="current-password"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-0"
                    name="remember">
                <span class="ms-2 text-sm" style="color: rgb(127,98,44);">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm"
                style="color: rgb(127,98,44);"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3"
                style="background-color: rgb(127,98,44); color: white;"
                onmouseover="this.style.backgroundColor='rgb(203,211,0)'"
                onmouseout="this.style.backgroundColor='rgb(127,98,44)'">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="underline text-sm" style="color: rgb(127,98,44);">
                {{ __('Create an account') }}
            </a>
        </div>
    </form>
</x-guest-layout>