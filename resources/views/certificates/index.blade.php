@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4" style="color: rgb(127,98,44);">Certificates</h2>

    <!-- Add Certificate Button -->
    <a href="{{ route('certificates.create') }}"
        class="px-4 py-2 rounded-lg font-semibold mb-4 inline-block"
        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
        Add Certificate
    </a>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
    <div class="px-4 py-2 mb-4 rounded-lg font-semibold"
        style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
        {{ session('success') }}
    </div>
    @endif

    <!-- FILTERS -->
    <div class="flex flex-wrap gap-4 mb-4">
        <input type="text" id="filterCourseId" placeholder="Filter by Course ID"
            class="px-3 py-2 rounded-lg border-2 border-[rgb(127,98,44)] text-[rgb(127,98,44)] placeholder-[rgb(127,98,44)] focus:outline-none focus:ring-0 focus:border-[rgb(127,98,44)] bg-white">

        <input type="date" id="filterDateFrom"
            class="px-3 py-2 rounded-lg border-2 border-[rgb(127,98,44)] text-[rgb(127,98,44)] focus:outline-none focus:ring-0 focus:border-[rgb(127,98,44)] bg-white">

        <input type="date" id="filterDateTo"
            class="px-3 py-2 rounded-lg border-2 border-[rgb(127,98,44)] text-[rgb(127,98,44)] focus:outline-none focus:ring-0 focus:border-[rgb(127,98,44)] bg-white">

        <button id="resetFilters" class="px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44);">Reset</button>
    </div>

    <!-- FILTERED RESULTS TABLE -->
    <div id="filteredResultsContainer" class="mb-6 hidden">
        <h3 class="text-lg font-bold mb-2" style="color: rgb(127,98,44);">Filtered Certificates</h3>
        <table class="w-full border-collapse" id="filteredCertificatesTable">
            <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
                <tr style="color: rgb(127,98,44); font-weight: 600;">
                    <th class="px-3 py-2">Course Name</th>
                    <th class="px-3 py-2">Course ID</th>
                    <th class="px-3 py-2">Certificate Name</th>
                    <th class="px-3 py-2">Date Created</th>
                    <th class="px-3 py-2">Total Participants</th>
                    <th class="px-3 py-2">Collected</th>
                    <th class="px-3 py-2">Not Collected</th>
                    <th class="px-3 py-2">File</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- MAIN CERTIFICATES TABLE -->
    <table class="w-full border-collapse" id="certificatesTable">
        <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
            <tr style="color: rgb(127,98,44); font-weight: 600;">
                <th class="px-3 py-2">Course Name</th>
                <th class="px-3 py-2">Total Certificates</th>
                <th class="px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $courseName => $courseCertificates)
            <tr class="hover:bg-[rgb(203,211,0)]/20 course-row cursor-pointer" data-course="{{ Str::slug($courseName) }}">
                <!-- Course Name cell - now non-clickable -->
                <td class="px-3 py-2 font-semibold" style="color: rgb(127,98,44);">
                    {{ $courseName }}
                </td>
                <td class="px-3 py-2" style="color: rgb(127,98,44);">{{ count($courseCertificates) }}</td>
                <td class="px-3 py-2 text-center">
                    <!-- Only arrow toggles sub-table -->
                    <svg class="w-5 h-5 cursor-pointer text-[rgb(127,98,44)] toggle-subtable"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </td>
            </tr>

            <!-- HIDDEN SUB-TABLE FOR THIS COURSE -->
            <tr class="sub-table-{{ Str::slug($courseName) }} hidden">
                <td colspan="3" class="p-0">
                    <table class="w-full border-collapse">
                        <thead style="background-color: rgb(245,247,200); border-bottom: 2px solid rgb(203,211,0);">
                            <tr style="color: rgb(127,98,44); font-weight: 600;">
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Course ID</th>
                                <th class="px-3 py-2">Certificate Name</th>
                                <th class="px-3 py-2">Date Created</th>
                                <th class="px-3 py-2">Total Participants</th>
                                <th class="px-3 py-2">Collected</th>
                                <th class="px-3 py-2">Not Collected</th>
                                <th class="px-3 py-2">File</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courseCertificates as $certificate)
                            <tr class="hover:bg-[rgb(203,211,0)]/10 certificate-row sub-table-row" data-course-name="{{ $courseName }}">
                                <!-- Clickable ID cell linking to participants page -->
                                <td class="px-3 py-2">
                                    <a href="{{ route('certificates.participants', $certificate->course_id) }}"
                                        class="text-[rgb(127,98,44)] hover:underline font-semibold">
                                        {{ $certificate->id }}
                                    </a>
                                </td>
                                <td class="certificate-id px-3 py-2">
                                    <a href="{{ route('certificates.participants', $certificate->course_id) }}"
                                        class="text-[rgb(127,98,44)] hover:underline">
                                        {{ $certificate->course_id }}
                                    </a>
                                </td>
                                <td class="certificate-name px-3 py-2">
                                    <a href="{{ route('certificates.participants', $certificate->course_id) }}"
                                        class="text-[rgb(127,98,44)] hover:underline">
                                        {{ $certificate->certificate_name }}
                                    </a>
                                </td>
                                <td class="certificate-date px-3 py-2">{{ \Carbon\Carbon::parse($certificate->issue_date)->format('Y-m-d') }}</td>
                                <td class="certificate-total px-3 py-2">{{ $certificate->participants_count }}</td>
                                <td class="certificate-collected px-3 py-2">{{ $certificate->collected_count }}</td>
                                <td class="certificate-notcollected px-3 py-2">{{ $certificate->not_collected_count }}</td>
                                <td class="certificate-file px-3 py-2">
                                    @if($certificate->certificate_file)
                                    <a href="{{ asset('storage/'.$certificate->certificate_file) }}" target="_blank">View</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('certificates.edit', $certificate->id) }}"
                                        class="px-3 py-1 rounded-lg font-semibold"
                                        style="background-color: rgb(203,211,0); color: rgb(127,98,44); margin-right: 4px;">
                                        Edit
                                    </a>
                                    <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded-lg font-semibold"
                                            style="background-color: rgb(245,100,100); color: white;"
                                            onclick="return confirm('Delete this certificate?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Toggle sub-tables and filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterCourseId = document.getElementById('filterCourseId');
            const filterDateFrom = document.getElementById('filterDateFrom');
            const filterDateTo = document.getElementById('filterDateTo');
            const resetFilters = document.getElementById('resetFilters');

            const filteredContainer = document.getElementById('filteredResultsContainer');
            const filteredBody = document.querySelector('#filteredCertificatesTable tbody');

            // Toggle sub-tables when arrow clicked
            document.querySelectorAll('.toggle-subtable').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent row click from firing
                    const row = e.target.closest('tr');
                    const courseSlug = row.dataset.course;
                    const subTable = document.querySelector('.sub-table-' + courseSlug);
                    subTable.classList.toggle('hidden');
                });
            });

            // Filter function
            function filterCertificates() {
                filteredBody.innerHTML = '';
                let anyMatch = false;

                document.querySelectorAll('tr.sub-table-row').forEach(certRow => {
                    const courseName = certRow.dataset.courseName;
                    const courseId = certRow.querySelector('.certificate-id').textContent.trim();
                    const certName = certRow.querySelector('.certificate-name').textContent.trim();
                    const dateStr = certRow.querySelector('.certificate-date').textContent.trim();
                    const totalParticipants = certRow.querySelector('.certificate-total').textContent.trim();
                    const collected = certRow.querySelector('.certificate-collected').textContent.trim();
                    const notCollected = certRow.querySelector('.certificate-notcollected').textContent.trim();
                    const fileLink = certRow.querySelector('.certificate-file a') ? certRow.querySelector('.certificate-file a').href : null;

                    const rowDate = new Date(dateStr);
                    const fromDate = filterDateFrom.value ? new Date(filterDateFrom.value) : null;
                    const toDate = filterDateTo.value ? new Date(filterDateTo.value) : null;

                    let match = true;

                    if (filterCourseId.value && !courseId.includes(filterCourseId.value.trim())) match = false;
                    if (fromDate && rowDate < fromDate) match = false;
                    if (toDate && rowDate > toDate) match = false;

                    if (match) {
                        anyMatch = true;
                        filteredBody.innerHTML += `
                <tr class="hover:bg-[rgb(203,211,0)]/10">
                    <td class="px-3 py-2">${courseName}</td>
                    <td class="px-3 py-2">${courseId}</td>
                    <td class="px-3 py-2">${certName}</td>
                    <td class="px-3 py-2">${dateStr}</td>
                    <td class="px-3 py-2">${totalParticipants}</td>
                    <td class="px-3 py-2">${collected}</td>
                    <td class="px-3 py-2">${notCollected}</td>
                    <td class="px-3 py-2">${fileLink ? `<a href="${fileLink}" target="_blank" class="px-3 py-1 rounded-lg font-semibold" style="background-color: rgb(203,211,0); color: rgb(127,98,44);">View</a>` : '-'}</td>
                </tr>
                `;
                    }
                });

                filteredContainer.style.display = anyMatch ? 'block' : 'none';
            }

            filterCourseId.addEventListener('keyup', filterCertificates);
            filterDateFrom.addEventListener('change', filterCertificates);
            filterDateTo.addEventListener('change', filterCertificates);

            resetFilters.addEventListener('click', function() {
                filterCourseId.value = '';
                filterDateFrom.value = '';
                filterDateTo.value = '';
                filteredBody.innerHTML = '';
                filteredContainer.style.display = 'none';
            });
        });
    </script>
    @endsection