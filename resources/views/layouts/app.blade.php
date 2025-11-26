<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSG-eLTi Certificate Tracker</title>
    <link rel="icon" href="{{ asset('assets/images/KSG Logo (1).png') }}" type="image/png" />

    <!-- CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite('resources/css/app.css')

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color: rgb(127,98,44); min-height:100vh;">

    <div style="display:flex; min-height:100vh;">

        <!-- SIDEBAR -->
        <aside id="sidebar" style="width:16rem; min-height:100vh; display:flex; flex-direction:column; background-color: rgb(203,211,0); color: rgb(127,98,44); box-shadow:0 0 10px rgba(0,0,0,0.1);">

            <!-- Sidebar Header -->
            <div style="padding:1.5rem; border-bottom:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; gap:0.75rem;">
                <img src="{{ asset('assets/images/KSG Logo (1).png') }}" alt="KSG Logo" style="width:1.5rem; height:1.5rem; object-contain;">
                <h2 style="font-size:1.25rem; font-weight:600; margin:0;">KSG-eLiTi Certificate Tracker</h2>
            </div>

            <!-- Navigation -->
            <nav style="padding:1rem; flex:1; display:flex; flex-direction:column; gap:0.5rem;">
                <a href="/dashboard"
                    style="display:flex; align-items:center; padding:0.75rem; border-radius:0.5rem; color: rgb(127,98,44); text-decoration:none; font-weight:500;"
                    onmouseover="this.style.backgroundColor='rgba(203,211,0,0.8)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fa-solid fa-gauge mr-2" style="margin-right:0.5rem;"></i> Dashboard
                </a>
                <a href="/certificates"
                    style="display:flex; align-items:center; padding:0.75rem; border-radius:0.5rem; color: rgb(127,98,44); text-decoration:none; font-weight:500;"
                    onmouseover="this.style.backgroundColor='rgba(203,211,0,0.8)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fa-solid fa-certificate mr-2" style="margin-right:0.5rem;"></i> Certificates
                </a>
                <a href="/participants"
                    style="display:flex; align-items:center; padding:0.75rem; border-radius:0.5rem; color: rgb(127,98,44); text-decoration:none; font-weight:500;"
                    onmouseover="this.style.backgroundColor='rgba(203,211,0,0.8)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fa-solid fa-users mr-2" style="margin-right:0.5rem;"></i> Participants
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main style="flex:1; padding:1.5rem; background-color:#f3f4f6; color: rgb(127,98,44);">
            @yield('content')
        </main>

        <!-- MOBILE TOGGLE BUTTON -->
        <div class="md:hidden" style="position:fixed; top:1rem; left:1rem; z-index:50;">
            <button id="sidebarToggle" style="padding:0.5rem; background-color: rgb(203,211,0); color: rgb(127,98,44); border-radius:0.375rem; border:none; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" style="height:1.5rem; width:1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

    </div>

    <script>
        // Mobile toggle
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        toggle.addEventListener('click', () => sidebar.style.display = sidebar.style.display === 'none' ? 'flex' : 'none');
    </script>

</body>

</html>