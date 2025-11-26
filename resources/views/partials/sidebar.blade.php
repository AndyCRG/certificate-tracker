<aside class="w-64 bg-white h-screen fixed left-0 top-0 shadow-md">

    <div class="p-6 border-b">
        <h1 class="text-lg font-semibold">KSG-eLTi</h1>
    </div>

    <nav class="p-4 space-y-3">

        <p class="text-gray-700 font-medium">{{ Auth::user()->name }}</p>

        <a href="{{ route('dashboard') }}"
            class="block py-2 text-gray-700 hover:text-blue-600">
            Dashboard
        </a>

        <a href="{{ route('profile.edit') }}"
            class="block py-2 text-gray-700 hover:text-blue-600">
            Profile
        </a>

        <a href="{{ route('settings') }}"
            class="block py-2 text-gray-700 hover:text-blue-600">
            Settings
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="py-2 text-red-600 hover:text-red-800">
                Logout
            </button>
        </form>
    </nav>

</aside>