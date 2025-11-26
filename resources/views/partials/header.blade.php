<header class="bg-white shadow-sm h-[70px] flex items-center px-6 justify-between">

    {{-- LEFT SECTION (icon + title) --}}
    <div class="flex items-center gap-4">
        <!-- Left Icon -->
        <button class="text-gray-700 hover:text-gray-900">
            <i class="fa-solid fa-grip-lines fa-lg"></i>
        </button>

        <!-- Page Title -->
        <h2 class="text-xl font-semibold text-gray-800">
            @yield('page-title', 'Dashboard')
        </h2>
    </div>

    {{-- RIGHT SECTION (user profile dropdown) --}}
    <div class="relative" x-data="{ open: false }">

        <!-- Trigger -->
        <button @click="open = !open"
            class="flex items-center gap-3 text-gray-800 hover:text-gray-900">

            <img src="{{ asset('assets/images/user-avatar.png') }}"
                class="w-10 h-10 rounded-full">

            <span class="font-medium">
                {{ Auth::user()->name ?? 'User' }}
            </span>

            <i class="fa-solid fa-chevron-down text-sm"></i>
        </button>

        <!-- Dropdown -->
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-2 z-50">

            <a href="/profile"
                class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fa-regular fa-user"></i> Profile
            </a>

            <a href="/edit"
                class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
                <i class="fa-solid fa-gear"></i> Settings
            </a>

            <div class="border-t my-2"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>

        </div>
    </div>

</header>