@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4" style="color: rgb(127,98,44);">
        Participants for Course: {{ $courseId }}
    </h2>

    <a href="{{ route('certificates.index') }}"
        class="px-4 py-2 rounded-lg font-semibold mb-4 inline-block"
        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
        ← Back to Certificates
    </a>

    <!-- EXPORT BUTTON -->
    <a href="{{ route('course.export', $courseId) }}"
        class="px-4 py-2 rounded-lg font-semibold shadow-sm transition hover:scale-105"
        style="background-color: rgb(127,98,44); color: white;">

        Export Report
    </a>

    <!-- SEARCH FILTER -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 max-w-md">
                <input type="text"
                    id="participantSearch"
                    placeholder="Search participants by name or email..."
                    class="w-full px-4 py-2 rounded-lg border-2 border-[rgb(127,98,44)] text-[rgb(127,98,44)] placeholder-[rgb(127,98,44)] focus:outline-none focus:ring-0 focus:border-[rgb(127,98,44)] bg-white">
            </div>
            <button id="resetSearch"
                class="px-4 py-2 rounded-lg font-semibold"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                Clear Search
            </button>
        </div>

        <!-- Stats -->
        <div class="flex gap-3">

            <div class="px-4 py-2 rounded-xl bg-gray-50 border">
                <p class="text-xs text-gray-500">Total Participants</p>

                <h3 class="font-bold text-lg"
                    style="color: rgb(127,98,44);">

                    {{ count($participants) }}
                </h3>
            </div>

            <div class="px-4 py-2 rounded-xl"
                style="background-color: rgb(245,247,200);">

                <p class="text-xs"
                    style="color: rgb(127,98,44);">

                    Current View
                </p>

                <h3 id="searchCountDisplay"
                    class="font-bold text-lg"
                    style="color: rgb(127,98,44);">
                </h3>
            </div>

        </div>
    </div>

    <!-- NO RESULTS MESSAGE -->
    <div id="noResultsMessage" class="mb-4 p-4 rounded-lg text-center hidden"
        style="background-color: rgb(245,247,200); color: rgb(127,98,44);">
        No participants found matching your search.
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto border rounded-xl">

        <table class="w-full">

            <thead style="background-color: rgb(245,247,200);">

                <tr class="text-left"
                    style="color: rgb(127,98,44);">

                    <th class="px-4 py-3 text-sm font-bold">#</th>
                    <th class="px-4 py-3 text-sm font-bold">Participant</th>
                    <th class="px-4 py-3 text-sm font-bold">Phone</th>
                    <th class="px-4 py-3 text-sm font-bold">Organization</th>
                    <th class="px-4 py-3 text-sm font-bold">Certificate</th>
                    <th class="px-4 py-3 text-sm font-bold text-center">Status</th>

                </tr>

            </thead>

            <tbody id="participantsTableBody">

                @if(count($participants) == 0)
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        No participants found for this course.
                    </td>
                </tr>
                @endif

                @foreach($participants as $index => $p)

                <tr class="participant-row border-t hover:bg-yellow-50 cursor-pointer transition"
                    onclick="window.location.href='{{ route('participants.show', $p['email']) }}'">

                    <!-- NUMBER -->
                    <td class="px-4 py-3 row-number">
                        {{ $index + 1 }}
                    </td>

                    <!-- NAME -->
                    <td class="px-4 py-3">

                        <div class="participant-name font-semibold text-gray-800">
                            {{ $p['name'] }}
                        </div>

                        <div class="participant-email text-xs text-gray-500">
                            {{ $p['email'] }}
                        </div>

                    </td>

                    <!-- PHONE -->
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $p['phone'] }}
                    </td>

                    <!-- ORGANIZATION -->
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $p['organization'] }}
                    </td>

                    <!-- CERTIFICATE -->
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $p['certificate_name'] }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-3 text-center">

                        @if($p['collected'])

                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold"
                            style="background-color: rgba(203,211,0,0.2); color: rgb(127,98,44);">

                            <span class="w-2 h-2 rounded-full"
                                style="background-color: rgb(203,211,0);">
                            </span>

                            Collected
                        </span>

                        @else

                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">

                            <span class="w-2 h-2 rounded-full bg-red-500">
                            </span>

                            Pending
                        </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="flex items-center justify-between mt-5">

        <button id="prevPage"
            class="px-4 py-2 rounded-lg font-semibold disabled:opacity-40"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44);">

            ← Previous
        </button>

        <div id="pageInfo"
            class="font-semibold text-sm"
            style="color: rgb(127,98,44);">
        </div>

        <button id="nextPage"
            class="px-4 py-2 rounded-lg font-semibold disabled:opacity-40"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44);">

            Next →
        </button>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const rowsPerPage = 10;
        let currentPage = 1;

        const rows = Array.from(document.querySelectorAll('.participant-row'));

        const searchInput = document.getElementById('participantSearch');
        const resetButton = document.getElementById('resetSearch');

        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');

        const pageInfo = document.getElementById('pageInfo');

        const searchCount = document.getElementById('searchCountDisplay');

        const noResults = document.getElementById('noResultsMessage');

        let filteredRows = [...rows];

        function renderTable() {

            rows.forEach(row => row.style.display = 'none');

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            const paginatedRows = filteredRows.slice(start, end);

            paginatedRows.forEach((row, index) => {

                row.style.display = '';

                row.querySelector('.row-number').textContent = start + index + 1;
            });

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            pageInfo.textContent =
                `Page ${currentPage} of ${totalPages || 1}`;

            searchCount.textContent =
                `${filteredRows.length} Showing`;

            prevPage.disabled = currentPage === 1;

            nextPage.disabled = currentPage === totalPages || totalPages === 0;

            noResults.classList.toggle('hidden', filteredRows.length > 0);
        }

        function filterRows() {

            const term = searchInput.value.toLowerCase();

            filteredRows = rows.filter(row => {

                const name = row.querySelector('.participant-name').textContent.toLowerCase();

                const email = row.querySelector('.participant-email').textContent.toLowerCase();

                return name.includes(term) || email.includes(term);
            });

            currentPage = 1;

            renderTable();
        }

        searchInput.addEventListener('input', filterRows);

        resetButton.addEventListener('click', () => {

            searchInput.value = '';

            filteredRows = [...rows];

            currentPage = 1;

            renderTable();
        });

        prevPage.addEventListener('click', () => {

            if (currentPage > 1) {

                currentPage--;

                renderTable();
            }
        });

        nextPage.addEventListener('click', () => {

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            if (currentPage < totalPages) {

                currentPage++;

                renderTable();
            }
        });

        renderTable();

    });
</script>
@endsection