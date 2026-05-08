@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded-xl shadow-md" style="color: rgb(127,98,44);">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">

        <div>
            <h2 class="text-2xl font-bold tracking-tight"
                style="color: rgb(127,98,44);">
                Participants Dashboard
            </h2>

            <p class="text-xs mt-1"
                style="color: rgb(127,98,44); opacity: .8;">
                Manage participant uploads, enrollments and certificate tracking.
            </p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex flex-wrap gap-2">

            <a href="{{ route('participants.create') }}"
                class="px-4 py-2 rounded-lg font-semibold mb-4 inline-block"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                + Add Participant
            </a>

            <!-- <button onclick="document.getElementById('uploadModal').showModal()"
                class="px-4 py-2 rounded-lg font-semibold transition duration-200"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">

                Bulk Upload
            </button> -->

        </div>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
    <div class="mb-5 p-4 rounded-xl border-l-4 shadow-sm flex justify-between items-center"
        style="background-color: rgb(245,247,200); border-color: rgb(203,211,0); color: rgb(127,98,44);">

        <span class="font-medium">{{ session('success') }}</span>

        @if(session('batch_id'))
        <button onclick="document.getElementById('undoModal').showModal()"
            class="underline font-semibold">
            Undo Upload
        </button>
        @endif
    </div>
    @endif

    <!-- ERRORS -->
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-xl mb-5">
        <ul class="list-disc pl-5 text-red-700">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- FILTER SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-[rgb(203,211,0)]/30 p-4 mb-5">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">

            <h3 class="text-base font-semibold"
                style="color: rgb(127,98,44);">
                Filter Participants
            </h3>

            <button
                id="resetFilters"
                class="px-4 py-2 rounded-lg font-semibold shadow-sm transition duration-200"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">

                Reset Filters
            </button>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

            <!-- Course Name -->
            <div>
                <label class="block mb-1 text-xs font-semibold"
                    style="color: rgb(127,98,44);">
                    Course Name
                </label>

                <input
                    type="text"
                    id="filterCourseName"
                    placeholder="Search course..."
                    class="w-full px-3 py-2 rounded-lg
                       border-2 border-[rgb(127,98,44)]
                       text-black
                       placeholder-gray-500
                       focus:outline-none
                       focus:ring-2
                       focus:ring-[rgb(203,211,0)]
                       bg-white">
            </div>

            <!-- Course ID -->
            <div>
                <label class="block mb-1 text-xs font-semibold"
                    style="color: rgb(127,98,44);">
                    Course ID
                </label>

                <input
                    type="text"
                    id="filterCourseId"
                    placeholder="Search ID..."
                    class="w-full px-3 py-2 rounded-lg
                       border-2 border-[rgb(127,98,44)]
                       text-black
                       placeholder-gray-500
                       focus:outline-none
                       focus:ring-2
                       focus:ring-[rgb(203,211,0)]
                       bg-white">
            </div>

            <!-- Date From -->
            <div>
                <label class="block mb-1 text-xs font-semibold"
                    style="color: rgb(127,98,44);">
                    Date From
                </label>

                <input
                    type="date"
                    id="filterDateFrom"
                    class="w-full px-3 py-2 rounded-lg
                       border-2 border-[rgb(127,98,44)]
                       text-black
                       focus:outline-none
                       focus:ring-2
                       focus:ring-[rgb(203,211,0)]
                       bg-white">
            </div>

            <!-- Date To -->
            <div>
                <label class="block mb-1 text-xs font-semibold"
                    style="color: rgb(127,98,44);">
                    Date To
                </label>

                <input
                    type="date"
                    id="filterDateTo"
                    class="w-full px-3 py-2 rounded-lg
                       border-2 border-[rgb(127,98,44)]
                       text-black
                       focus:outline-none
                       focus:ring-2
                       focus:ring-[rgb(203,211,0)]
                       bg-white">
            </div>

        </div>

    </div>

    <table id="participantsTable" class="table table-striped table-bordered w-full" style="color: rgb(127,98,44);">
        <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
            <tr style="color: rgb(127,98,44); font-weight: 600;">
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Organization</th>
                <th>Courses</th>
                <th>Total Collected</th>
                <th>Not Collected</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($participants as $p)
            <tr class="hover:bg-[rgb(203,211,0)]/20">
                <td style="color: rgb(127,98,44);">{{ $p->name }}</td>
                <td style="color: rgb(127,98,44);">{{ $p->email }}</td>
                <td style="color: rgb(127,98,44);">{{ $p->phone }}</td>
                <td style="color: rgb(127,98,44);">{{ $p->organization }}</td>

                <!-- Number of Courses -->
                <td style="color: rgb(127,98,44);">{{ $p->totalCourses }}</td>

                <!-- Total Collected -->
                <td style="color: rgb(127,98,44);">
                    <span class="px-3 py-1 rounded-lg font-normal"
                        style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
                        {{ $p->collected }}
                    </span>
                </td>

                <!-- Not Collected -->
                <td style="color: rgb(127,98,44);">
                    <span class="px-3 py-1 rounded-lg font-normal"
                        style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
                        {{ $p->notCollected }}
                    </span>
                </td>

                <!-- Action -->
                <td>
                    <a href="{{ route('participants.show', $p->email) }}"
                        class="px-3 py-1 rounded-lg font-normal"
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                        View
                    </a>
                </td>

                <!-- HIDDEN COLUMNS FOR FILTERING -->
                <td style="display:none;">{{ $p->course_name }}</td> <!-- column 8 -->
                <td style="display:none;">{{ $p->course_id }}</td> <!-- column 9 -->
                <td style="display:none;">{{ $p->course_date }}</td> <!-- column 10 -->
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- UPLOAD MODAL -->
<dialog id="uploadModal" class="p-6 rounded-lg shadow-xl w-96">
    <h2 class="text-xl font-bold mb-3" style="color: rgb(127,98,44);">Upload Participants (Excel/CSV)</h2>

    <form action="{{ route('participants.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" class="mb-4 w-full border p-2 rounded" required style="color: rgb(127,98,44);">
        <button class="w-full px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44); hover:brightness(90%);">
            Upload File
        </button>
    </form>

    <button onclick="document.getElementById('uploadModal').close()"
        class="mt-4 underline w-full" style="color: rgb(127,98,44);">
        Cancel
    </button>
</dialog>

<!-- UNDO MODAL -->
<dialog id="undoModal" class="p-6 rounded-lg shadow-xl w-80">
    <h2 class="text-xl font-bold mb-3" style="color: rgb(127,98,44);">Undo Last Upload?</h2>

    <form action="{{ route('participants.undo') }}" method="POST">
        @csrf
        <input type="hidden" name="batch_id" value="{{ session('batch_id') }}">

        <button class="w-full px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44); hover:brightness(90%);">
            Yes, Undo Upload
        </button>
    </form>

    <button onclick="document.getElementById('undoModal').close()"
        class="mt-4 underline w-full" style="color: rgb(127,98,44);">
        Cancel
    </button>
</dialog>

<script>
    $(document).ready(function() {

        // Initialise DataTable ONCE
        const table = $('#participantsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [{
                    targets: [8, 9, 10],
                    visible: false
                } // Hide Course Name, Course ID, Date columns
            ]
        });

        // Course Name filter (hidden column 8)
        $('#filterCourseName').on('keyup change', function() {
            table.column(8).search(this.value).draw();
        });

        // Course ID filter (hidden column 9)
        $('#filterCourseId').on('keyup change', function() {
            table.column(9).search(this.value).draw();
        });

        // Date range filter (hidden column 10)
        $.fn.dataTable.ext.search.push(function(settings, data) {
            let from = $('#filterDateFrom').val();
            let to = $('#filterDateTo').val();
            let date = data[10]; // hidden date column

            if (!from && !to) return true;
            if (!date) return false;

            if (from && date < from) return false;
            if (to && date > to) return false;

            return true;
        });

        $('#filterDateFrom, #filterDateTo').on('change', function() {
            table.draw();
        });

        // Reset filters
        $('#resetFilters').on('click', function() {
            $('#filterCourseName').val('');
            $('#filterCourseId').val('');
            $('#filterDateFrom').val('');
            $('#filterDateTo').val('');
            table.search('').columns().search('').draw();
        });

    });
</script>
@endsection