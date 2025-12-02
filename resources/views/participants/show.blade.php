@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4" style="color: rgb(127,98,44);">
        Certificates for {{ $participant->name }}
    </h2>

    @php
    $allCertificates = DB::table('certificate_participant')
    ->join('certificates', 'certificate_participant.certificate_id', '=', 'certificates.id')
    ->where('certificate_participant.participant_email', $participant->email)
    ->select(
    'certificates.*',
    'certificate_participant.collected',
    'certificate_participant.collected_by',
    'certificate_participant.collected_at',
    'certificate_participant.participant_email'
    )
    ->get();

    $totalCourses = $allCertificates->pluck('course_id')->unique()->count();
    $totalCollected = $allCertificates->where('collected', 1)->count();
    $totalNotCollected = $allCertificates->where('collected', 0)->count();
    @endphp

    <div id="certificateCounters" class="mb-4 p-4 bg-yellow-100 rounded">
        <p class="text-[rgb(127,98,44)] font-semibold">
            Total Courses Taken: <span id="totalCourses">{{ $totalCourses }}</span> |
            Certificates Collected: <span id="totalCollected">{{ $totalCollected }}</span> |
            Certificates Not Collected: <span id="totalNotCollected">{{ $totalNotCollected }}</span>
        </p>
    </div>

    <table id="certificatesTable" class="w-full border-collapse">
        <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
            <tr style="color: rgb(127,98,44); font-weight: normal;">
                <th>Course ID</th>
                <th>Course</th>
                <th>Issued By</th>
                <th>Date Generated</th>
                <th>Collected</th>
                <th>Collected By</th>
                <th>Date Picked</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($allCertificates as $c)
            <tr>
                <td>{{ $c->course_id }}</td>
                <td>{{ $c->course_name ?? '-' }}</td>
                <td>{{ $c->issued_by ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d-M-Y') }}</td>
                <td class="collected-status">{{ $c->collected ? 'Collected' : 'Not Collected' }}</td>
                <td class="collected-by">{{ $c->collected_by ?? 'N/A' }}</td>
                <td>{{ $c->collected_at ? \Carbon\Carbon::parse($c->collected_at)->format('d-M-Y H:i') : 'N/A' }}</td>
                <td>
                    <button type="button"
                        class="px-3 py-1 rounded mark-collected-btn"
                        data-id="{{ $c->id }}"
                        data-email="{{ $c->participant_email }}"
                        {{ $c->collected ? 'disabled' : '' }}
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                        Mark as Collected
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Collected By Modal -->
<div id="collectedByModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" style="z-index: 9999;">
    <div class="bg-white p-4 rounded-lg w-80 shadow-lg">
        <h3 class="text-lg font-semibold mb-2" style="color: rgb(127,98,44);">Collected By</h3>
        <input
            type="text"
            id="collectedByInput"
            class="w-full p-2 mb-3"
            placeholder="Enter name"
            style="border: 2px solid rgb(203,211,0); outline: none; color: rgb(127,98,44); border-radius: 6px;">
        <button
            id="cancelCollectedBy"
            class="px-3 py-1 rounded"
            style="background-color: rgb(245,247,200); color: rgb(127,98,44);">
            Cancel
        </button>
        <button
            id="saveCollectedBy"
            class="px-3 py-1 rounded"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
            Save
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('collectedByModal');
        const input = document.getElementById('collectedByInput');
        const saveBtn = document.getElementById('saveCollectedBy');
        const cancelBtn = document.getElementById('cancelCollectedBy');
        let currentButton = null;

        // Update counters for collected / not collected
        function updateCounters() {
            const rows = document.querySelectorAll('#certificatesTable tbody tr');
            let collectedCount = 0,
                notCollectedCount = 0;

            rows.forEach(row => {
                if (row.querySelector('.collected-status').textContent.trim() === 'Collected') {
                    collectedCount++;
                } else {
                    notCollectedCount++;
                }
            });

            document.getElementById('totalCollected').textContent = collectedCount;
            document.getElementById('totalNotCollected').textContent = notCollectedCount;
        }

        // Update a specific row after save
        function updateRow(button, data) {
            const row = button.closest('tr');
            if (!row) return;

            row.querySelector('.collected-status').textContent = data.collected ? 'Collected' : 'Not Collected';
            row.querySelector('.collected-by').textContent = data.collected_by || 'N/A';
            row.querySelector('td:nth-child(7)').textContent = data.collected_at || 'N/A';
            button.disabled = data.collected;

            updateCounters();
        }

        // Event delegation for dynamic buttons
        document.getElementById('certificatesTable').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('mark-collected-btn')) {
                currentButton = e.target;
                input.value = '';
                modal.classList.remove('hidden');
                input.focus();
            }
        });

        // Save button logic
        saveBtn.addEventListener('click', function() {
            if (!currentButton) {
                alert('No row selected!');
                return;
            }

            const name = input.value.trim();
            if (!name) {
                alert('Please enter a name');
                return;
            }

            const id = currentButton.dataset.id;
            const participantEmail = currentButton.dataset.email;

            console.log('Saving collected by:', {
                id,
                participantEmail,
                name
            });

            fetch(`/certificates/${id}/toggle-collected`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        participant_email: participantEmail,
                        collected_by: name
                    })
                })
                .then(async res => {
                    const contentType = res.headers.get('content-type');
                    let data = {};

                    // Parse JSON only if content-type is JSON
                    if (contentType && contentType.includes('application/json')) {
                        data = await res.json();
                    }

                    if (!res.ok) {
                        console.error('HTTP Error:', res.status, data);
                        alert(data.message || `Server error ${res.status}`);
                        return;
                    }

                    if (data.success) {
                        updateRow(currentButton, data);
                        modal.classList.add('hidden');
                        currentButton = null;
                    } else {
                        alert(data.message || 'Failed to update');
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Error updating status! Check console for details.');
                });
        });

        // Cancel button
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            currentButton = null;
        });
    });
</script>


@endsection