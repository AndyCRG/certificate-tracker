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
        <div id="searchCount" class="mt-2 text-sm" style="color: rgb(127,98,44);"></div>
    </div>

    <!-- NO RESULTS MESSAGE -->
    <div id="noResultsMessage" class="mb-4 p-4 rounded-lg text-center hidden"
        style="background-color: rgb(245,247,200); color: rgb(127,98,44);">
        No participants found matching your search.
    </div>

    <!-- PARTICIPANTS TABLE -->
    <div id="participantsContainer">
        <table class="w-full border-collapse">
            <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
                <tr style="color: rgb(127,98,44); font-weight: 600;">
                    <th class="px-3 py-2">Name</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Phone</th>
                    <th class="px-3 py-2">Organization</th>
                    <th class="px-3 py-2">Certificate</th>
                    <th class="px-3 py-2">Collected</th>
                    <th class="px-3 py-2">Not Collected</th>
                </tr>
            </thead>
            <tbody id="participantsTableBody">
                @foreach($participants as $p)
                <tr class="hover:bg-[rgb(203,211,0)]/20 participant-row cursor-pointer transition-all duration-200"
                    data-email="{{ $p['email'] }}"
                    onclick="window.location.href='{{ route('participants.show', $p['email']) }}'">
                    <td class="px-3 py-2 participant-name">{{ $p['name'] }}</td>
                    <td class="px-3 py-2 participant-email">{{ $p['email'] }}</td>
                    <td class="px-3 py-2 participant-phone">{{ $p['phone'] }}</td>
                    <td class="px-3 py-2 participant-organization">{{ $p['organization'] }}</td>
                    <td class="px-3 py-2 participant-certificate">{{ $p['certificate_name'] }}</td>
                    <td class="px-3 py-2 participant-collected">
                        @if($p['collected'])
                        <span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                            Yes
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-3 py-2 participant-notcollected">
                        @if($p['not_collected'])
                        <span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: rgb(245,100,100); color: white;">
                            Yes
                        </span>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('participantSearch');
        const resetButton = document.getElementById('resetSearch');
        const participantsTable = document.getElementById('participantsTableBody');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const searchCount = document.getElementById('searchCount');
        const originalRows = Array.from(participantsTable.querySelectorAll('.participant-row'));
        const totalParticipants = originalRows.length;

        // Store original onclick handlers for each row
        originalRows.forEach(row => {
            row._originalOnClick = row.onclick;
        });

        // Initialize search count
        updateSearchCount(totalParticipants, totalParticipants);

        function updateSearchCount(shown, total) {
            searchCount.textContent = `Showing ${shown} of ${total} participants`;
        }

        function filterParticipants() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            if (searchTerm === '') {
                // Show all rows and restore click handlers
                originalRows.forEach((row, index) => {
                    row.style.display = '';
                    // Restore original onclick handler
                    row.onclick = row._originalOnClick;
                    row.style.cursor = 'pointer';
                    row.classList.add('hover:bg-[rgb(203,211,0)]/20');
                });
                noResultsMessage.classList.add('hidden');
                updateSearchCount(totalParticipants, totalParticipants);
                return;
            }

            // Filter rows
            originalRows.forEach(row => {
                const name = row.querySelector('.participant-name').textContent.toLowerCase();
                const email = row.querySelector('.participant-email').textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                    // Restore click functionality for visible rows
                    row.onclick = row._originalOnClick;
                    row.style.cursor = 'pointer';
                    row.classList.add('hover:bg-[rgb(203,211,0)]/20');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    // Remove click functionality for hidden rows
                    row.onclick = null;
                    row.style.cursor = 'default';
                    row.classList.remove('hover:bg-[rgb(203,211,0)]/20');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }

            updateSearchCount(visibleCount, totalParticipants);
        }

        // Event listeners
        searchInput.addEventListener('input', filterParticipants);

        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            filterParticipants();
            searchInput.focus();
        });

        // Prevent clicks on table headers from triggering navigation
        document.querySelectorAll('thead tr th').forEach(th => {
            th.style.cursor = 'default';
            th.onclick = function(e) {
                e.stopPropagation();
                return false;
            };
        });

        // Initial focus on search input
        searchInput.focus();
    });
</script>
@endsection