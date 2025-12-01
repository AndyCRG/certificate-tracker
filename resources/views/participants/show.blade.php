@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4" style="color: rgb(127,98,44);">
        Certificates for {{ $participant->name }}
    </h2>

    @php
    $allCertificates = \Illuminate\Support\Facades\DB::table('certificate_participant')
    ->join('certificates', 'certificate_participant.certificate_id', '=', 'certificates.id')
    ->where('certificate_participant.participant_email', $participant->email)
    ->select('certificates.*')
    ->get();

    $totalCourses = $allCertificates->pluck('course_id')->unique()->count();
    $collectedCertificates = $allCertificates->where('collected', 1);
    $notCollectedCertificates = $allCertificates->where('collected', 0);

    $totalCollected = $collectedCertificates->count();
    $totalNotCollected = $notCollectedCertificates->count();
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
            </tr>
        </thead>

        {{-- Collected Certificates --}}
        <tbody id="collectedBody">
            @foreach($collectedCertificates as $c)
            <tr class="certificate-row">
                <td>{{ $c->course_id }}</td>
                <td>{{ $c->course_name ?? '-' }}</td>
                <td>{{ $c->issued_by ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d-M-Y') }}</td>
                <td>
                    <button type="button"
                        class="px-3 py-1 rounded-lg font-normal toggle-collected"
                        data-id="{{ $c->id }}"
                        data-email="{{ $participant->email }}"
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                        Collected
                    </button>
                </td>
                <td class="collected-by">{{ $c->collected_by ?? 'N/A' }}</td>
                <td>{{ $c->collected_at ? \Carbon\Carbon::parse($c->collected_at)->format('d-M-Y H:i') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>

        {{-- Not Collected Certificates --}}
        <tbody id="notCollectedBody">
            @foreach($notCollectedCertificates as $c)
            <tr class="certificate-row">
                <td>{{ $c->course_id }}</td>
                <td>{{ $c->course_name ?? '-' }}</td>
                <td>{{ $c->issued_by ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d-M-Y') }}</td>
                <td>
                    <button type="button"
                        class="px-3 py-1 rounded-lg font-normal toggle-collected"
                        data-id="{{ $c->id }}"
                        data-email="{{ $participant->email }}"
                        style="background-color: rgb(245,247,200); color: rgb(127,98,44);">
                        Not Collected
                    </button>
                </td>
                <td class="collected-by">{{ $c->collected_by ?? 'N/A' }}</td>
                <td>{{ $c->collected_at ? \Carbon\Carbon::parse($c->collected_at)->format('d-M-Y H:i') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('collectedByModal');
        const input = document.getElementById('collectedByInput');
        const saveBtn = document.getElementById('saveCollectedBy');
        const cancelBtn = document.getElementById('cancelCollectedBy');
        let currentButton = null;

        function updateCounters(collectedCount, notCollectedCount) {
            document.getElementById('totalCollected').textContent = collectedCount;
            document.getElementById('totalNotCollected').textContent = notCollectedCount;
        }

        function attachToggle(btn) {
            btn.addEventListener('click', function() {
                const isCollected = this.textContent.trim() === 'Collected';
                if (!isCollected) {
                    currentButton = this;
                    input.value = '';
                    modal.classList.remove('hidden');
                } else {
                    toggleCollected(this, '');
                }
            });
        }

        function toggleCollected(button, collectedBy) {
            const id = button.dataset.id;
            const participantEmail = button.dataset.email;
            const row = button.closest('tr');

            fetch(`/certificates/${id}/toggle-collected`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        participant_email: participantEmail,
                        collected_by: collectedBy
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        button.textContent = data.collected ? 'Collected' : 'Not Collected';
                        button.style.backgroundColor = data.collected ? 'rgb(203,211,0)' : 'rgb(245,247,200)';
                        row.querySelector('.collected-by').textContent = data.collected_by || 'N/A';
                        row.querySelector('td:nth-child(7)').textContent = data.collected_at || 'N/A';

                        const targetBody = data.collected ? document.getElementById('collectedBody') : document.getElementById('notCollectedBody');
                        targetBody.appendChild(row);

                        const collectedCount = document.getElementById('collectedBody').children.length;
                        const notCollectedCount = document.getElementById('notCollectedBody').children.length;
                        updateCounters(collectedCount, notCollectedCount);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error toggling status!');
                });
        }

        saveBtn.addEventListener('click', function() {
            const name = input.value.trim();
            if (name === '') {
                alert('Please enter a name');
                return;
            }
            toggleCollected(currentButton, name);
            modal.classList.add('hidden');
        });

        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        document.querySelectorAll('.toggle-collected').forEach(attachToggle);
    });
</script>

<!-- Collected By Modal -->
<div id="collectedByModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-4 rounded-lg w-80">
        <h3 class="text-lg font-semibold mb-2" style="color: rgb(127,98,44);">Collected By</h3>
        <input type="text" id="collectedByInput" class="w-full p-2 mb-3" placeholder="Enter name"
            style="border: 2px solid rgb(203,211,0); outline: none; color: rgb(127,98,44); border-radius: 6px;">
        <div class="flex justify-end gap-2">
            <button id="cancelCollectedBy" class="px-3 py-1 rounded" style="background-color: rgb(245,247,200); color: rgb(127,98,44);">Cancel</button>
            <button id="saveCollectedBy" class="px-3 py-1 rounded" style="background-color: rgb(203,211,0); color: rgb(127,98,44);">Save</button>
        </div>
    </div>
</div>
@endsection