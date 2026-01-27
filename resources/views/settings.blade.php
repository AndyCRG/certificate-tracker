@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <h2 class="text-2xl font-semibold" style="color: rgb(127,98,44);">
        Account Settings
    </h2>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="p-3 rounded"
        style="background-color: rgba(203,211,0,0.25); color: rgb(127,98,44);">
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Details --}}
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold mb-4" style="color: rgb(127,98,44);">
            Profile Information
        </h3>

        <form method="POST" action="{{ route('settings.profile.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    Name
                </label>
                <input type="text" name="name"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2"
                    style="border-color: rgb(203,211,0);"
                    value="{{ old('name', $user->name) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    Email
                </label>
                <input type="email" name="email"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2"
                    style="border-color: rgb(203,211,0);"
                    value="{{ old('email', $user->email) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    Role
                </label>
                <input type="text"
                    class="w-full border rounded p-2"
                    style="background-color: #f9fafb; border-color: rgb(203,211,0);"
                    value="{{ $user->role ?? 'User' }}" disabled>
            </div>

            <button type="submit"
                class="px-4 py-2 rounded font-medium"
                style="background-color: rgb(127,98,44); color: white;"
                onmouseover="this.style.backgroundColor='rgb(203,211,0)'"
                onmouseout="this.style.backgroundColor='rgb(127,98,44)'">
                Save Profile
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold mb-4" style="color: rgb(127,98,44);">
            Change Password
        </h3>

        <form method="POST" action="{{ route('settings.password.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    Current Password
                </label>
                <input type="password" name="current_password"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2"
                    style="border-color: rgb(203,211,0);">
                @error('current_password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    New Password
                </label>
                <input type="password" name="password"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2"
                    style="border-color: rgb(203,211,0);">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium"
                    style="color: rgb(127,98,44);">
                    Confirm New Password
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2"
                    style="border-color: rgb(203,211,0);">
            </div>

            <button type="submit"
                class="px-4 py-2 rounded font-medium"
                style="background-color: rgb(127,98,44); color: white;"
                onmouseover="this.style.backgroundColor='rgb(203,211,0)'"
                onmouseout="this.style.backgroundColor='rgb(127,98,44)'">
                Change Password
            </button>
        </form>
    </div>

</div>
@endsection