<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KSG-eLTi Certificate Tracker</title>
    <link rel="icon" href="{{ asset('assets/images/KSG Logo (1).png') }}" type="image/png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <!-- KSG Logo -->
        <div style="padding:1.5rem; border-bottom:1px solid rgba(255,255,255,0.2); display:flex; flex-direction:column; align-items:center; gap:0.5rem;">
            <img src="{{ asset('assets/images/KSG Logo (1).png') }}"
                alt="KSG Logo"
                style="width:5rem; height:5rem; object-contain;">
            <h2 style="font-size:1.25rem; font-weight:600; margin:0; text-align:center;">
                KSG-eLiTi Certificate Tracker
            </h2>
        </div>

        <!-- Login / Slot Card -->
        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-xl">
            {{ $slot }}
        </div>
    </div>

</body>

</html>