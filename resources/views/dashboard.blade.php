@extends('layouts.app')

@section('content')
<style>
    canvas {
        width: 120px !important;
        height: 120px !important;
        margin: 0 auto;
        display: block;
    }
</style>

<div class="w-full bg-gray-50 min-h-screen">

    <!-- ======= PAGE HEADER ======= -->
    <div class="max-w-6xl mx-auto px-6 pt-8">
        <h1 class="text-3xl font-semibold" style="color: rgb(127, 98, 44);">
            KSG eLiTi Certificate Tracker Dashboard
        </h1>
        <p class="text-sm mt-1" style="color: rgb(127, 98, 44);">
            Overview of certificate tracking system
        </p>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('participants.create') }}"
               class="px-4 py-2 rounded-lg font-semibold"
               style="background-color: rgb(203, 211, 0); color: rgb(127, 98, 44);">
                + Add Participant
            </a>

            <a href="{{ route('participants.index') }}"
               class="px-4 py-2 rounded-lg font-semibold"
               style="background-color: rgb(203, 211, 0); color: rgb(127, 98, 44);">
                Manage Participants
            </a>
        </div>
    </div>

    <!-- ======= METRICS WITH DONUT CHARTS ======= -->
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Certificates -->
        <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition">
            <h3 class="text-sm" style="color: rgb(127, 98, 44);">Total Certificates</h3>
            <canvas id="chartTotal" class="mt-4"></canvas>
            <p class="text-center font-bold mt-2 text-xl" style="color: rgb(127, 98, 44);">
                {{ $totalCertificates }}
            </p>
        </div>

        <!-- Certificates Collected -->
        <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition">
            <h3 class="text-sm" style="color: rgb(127, 98, 44);">Certificates Collected</h3>
            <canvas id="chartCollected" class="mt-4"></canvas>
            <p class="text-center font-bold mt-2 text-xl" style="color: rgb(127, 98, 44);">
                {{ $receivedCertificates }}
            </p>
        </div>

        <!-- Pending Collection -->
        <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition">
            <h3 class="text-sm" style="color: rgb(127, 98, 44);">Pending Collection</h3>
            <canvas id="chartPending" class="mt-4"></canvas>
            <p class="text-center font-bold mt-2 text-xl" style="color: rgb(127, 98, 44);">
                {{ $pendingCertificates }}
            </p>
        </div>

        <!-- Total Participants -->
        <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition">
            <h3 class="text-sm" style="color: rgb(127, 98, 44);">Total Participants</h3>
            <canvas id="chartParticipants" class="mt-4"></canvas>
            <p class="text-center font-bold mt-2 text-xl" style="color: rgb(127, 98, 44);">
                {{ $totalParticipants }}
            </p>
        </div>
    </div>


    <!-- ======= CHART SCRIPTS ======= -->
    <script>
        const primary = 'rgb(203, 211, 0)';
        const faded = 'rgba(203, 211, 0, 0.2)';

        const total = Number({{ $totalCertificates }});
        const received = Number({{ $receivedCertificates }});
        const pending = Number({{ $pendingCertificates }});
        const participants = Number({{ $totalParticipants }});

        // Helper: Safe percentage
        const percent = (value, total) => {
            if (total <= 0) return 0;
            return Math.round((value / total) * 100);
        };

        // Total Certificates (always 100% donut)
        new Chart(document.getElementById('chartTotal'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [100],
                    backgroundColor: [primary],
                    borderWidth: 0
                }]
            },
            options: { cutout: '70%' }
        });

        // Certificates Collected (%)
        new Chart(document.getElementById('chartCollected'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        percent(received, total),
                        100 - percent(received, total)
                    ],
                    backgroundColor: [primary, faded],
                    borderWidth: 0
                }]
            },
            options: { cutout: '70%' }
        });

        // Pending Certificates (%)
        new Chart(document.getElementById('chartPending'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        percent(pending, total),
                        100 - percent(pending, total)
                    ],
                    backgroundColor: [primary, faded],
                    borderWidth: 0
                }]
            },
            options: { cutout: '70%' }
        });

        // Total Participants (always 100% donut)
        new Chart(document.getElementById('chartParticipants'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [100],
                    backgroundColor: [primary],
                    borderWidth: 0
                }]
            },
            options: { cutout: '70%' }
        });
    </script>

</div>
@endsection
