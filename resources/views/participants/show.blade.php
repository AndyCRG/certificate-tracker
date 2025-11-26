@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4" style="color: rgb(127,98,44);">
        Certificates for {{ $participant->name }}
    </h2>

    @php
    // Fetch certificates via pivot table
    $allCertificates = \Illuminate\Support\Facades\DB::table('certificate_participant')
    ->join('certificates', 'certificate_participant.certificate_id', '=', 'certificates.id')
    ->where('certificate_participant.participant_email', $participant->email)
    ->select('certificates.*')
    ->get();

    // Count distinct courses
    $totalCourses = $allCertificates->pluck('course_id')->unique()->count();

    // Separate collected / not collected
    $collectedCertificates = $allCertificates->where('collected', 1);
    $notCollectedCertificates = $allCertificates->where('collected', 0);

    $totalCollected = $collectedCertificates->count();
    $totalNotCollected = $notCollectedCertificates->count();
    @endphp

    <div class="mb-4 p-4 bg-yellow-100 rounded">
        <p class="text-[rgb(127,98,44)] font-semibold">
            Total Courses Taken: {{ $totalCourses }} |
            Certificates Collected: {{ $totalCollected }} |
            Certificates Not Collected: {{ $totalNotCollected }}
        </p>
    </div>


    <table id="certificatesTable" class="w-full border-collapse">
        <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
            <tr style="color: rgb(127,98,44); font-weight: normal;">
                <th class="px-3 py-2">Certificate No</th>
                <th class="px-3 py-2">Course</th>
                <th class="px-3 py-2">Issued By</th>
                <th class="px-3 py-2">Date Generated</th>
                <th class="px-3 py-2">Collected</th>
                <th class="px-3 py-2">Collected By</th>
                <th class="px-3 py-2">Date Picked</th>
            </tr>
        </thead>

        <tbody>
            {{-- Collected Certificates --}}
            @foreach($collectedCertificates as $c)
            <tr class="hover:bg-[rgb(203,211,0)]/20">
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->certificate_no }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->course_name ?? '-' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->issued_by ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">
                    {{ \Carbon\Carbon::parse($c->created_at)->format('d-M-Y') }}
                </td>
                <td class="px-3 py-2">
                    <button type="button"
                        class="px-3 py-1 rounded-lg font-normal toggle-collected"
                        data-id="{{ $c->id }}"
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                        Collected
                    </button>
                </td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->collected_by ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">
                    {{ $c->collected_at ? \Carbon\Carbon::parse($c->collected_at)->format('d-M-Y H:i') : 'N/A' }}
                </td>
            </tr>
            @endforeach

            {{-- Not Collected Certificates --}}
            @foreach($notCollectedCertificates as $c)
            <tr class="hover:bg-[rgb(203,211,0)]/20">
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->certificate_no }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->course_name ?? '-' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->issued_by ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">
                    {{ \Carbon\Carbon::parse($c->created_at)->format('d-M-Y') }}
                </td>
                <td class="px-3 py-2">
                    <button type="button"
                        class="px-3 py-1 rounded-lg font-normal toggle-collected"
                        data-id="{{ $c->id }}"
                        style="background-color: rgb(245,247,200); color: rgb(127,98,44);">
                        Not Collected
                    </button>
                </td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">{{ $c->collected_by ?? 'N/A' }}</td>
                <td class="px-3 py-2 text-[rgb(127,98,44)]">
                    {{ $c->collected_at ? \Carbon\Carbon::parse($c->collected_at)->format('d-M-Y H:i') : 'N/A' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-collected').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const btn = this;

                fetch(`/certificates/${id}/toggle-collected`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update button text and background color
                            btn.textContent = data.collected ? 'Collected' : 'Not Collected';
                            btn.style.backgroundColor = data.collected ? 'rgb(203,211,0)' : 'rgb(245,247,200)';

                            // Optional: instantly update collected_by and collected_at
                            const row = btn.closest('tr');
                            row.querySelector('td:nth-child(6)').textContent = data.collected_by || 'N/A';
                            row.querySelector('td:nth-child(7)').textContent = data.collected_at ? data.collected_at : 'N/A';
                        }
                    });
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.toggle-collected').click(function() {
            let button = $(this);
            let certificateId = button.data('id');

            $.ajax({
                url: '/certificates/toggle-collected/' + certificateId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update button text and color
                        if (response.collected) {
                            button.text('Collected');
                            button.css({
                                'background-color': 'rgb(203,211,0)',
                                'color': 'rgb(127,98,44)'
                            });
                        } else {
                            button.text('Not Collected');
                            button.css({
                                'background-color': 'rgb(245,247,200)',
                                'color': 'rgb(127,98,44)'
                            });
                        }
                    }
                },
                error: function(xhr) {
                    alert('Error toggling status!');
                }
            });
        });
    });
</script>
@endsection