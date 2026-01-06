<x-guest-layout>
    <h2 class="text-2xl font-semibold text-center mb-6" style="color: rgb(127,98,44);">
        Create an Account
    </h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" style="color: rgb(127,98,44);" />
            <x-text-input id="name" class="block mt-1 w-full border rounded p-2 focus:outline-none"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" style="color: rgb(127,98,44);" />
            <x-text-input id="email" class="block mt-1 w-full border rounded p-2 focus:outline-none"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" style="color: rgb(127,98,44);" />
            <x-text-input id="password" class="block mt-1 w-full border rounded p-2 focus:outline-none"
                type="password" name="password" required autocomplete="new-password"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="color: rgb(127,98,44);" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border rounded p-2 focus:outline-none"
                type="password" name="password_confirmation" required autocomplete="new-password"
                style="border-color: rgb(203,211,0);"
                onfocus="this.style.boxShadow='0 0 0 2px rgb(127,98,44)'"
                onblur="this.style.boxShadow='none'" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}"
                class="underline text-sm"
                style="color: rgb(127,98,44);">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4"
                style="background-color: rgb(127,98,44); color:white;"
                onmouseover="this.style.backgroundColor='rgb(203,211,0)'"
                onmouseout="this.style.backgroundColor='rgb(127,98,44)'">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>