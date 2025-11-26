@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded-xl shadow-md" style="color: rgb(127,98,44);">

    <h2 class="text-xl font-bold mb-4" style="color: rgb(127,98,44);">Participants</h2>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
    <div class="bg-green-100 p-3 rounded mb-4 flex justify-between items-center" style="color: rgb(127,98,44);">
        <span>{{ session('success') }}</span>

        @if(session('batch_id'))
        <button onclick="document.getElementById('undoModal').showModal()"
            class="underline ml-3">
            Undo Upload
        </button>
        @endif
    </div>
    @endif

    <!-- VALIDATION ERRORS -->
    @if ($errors->any())
    <div class="bg-red-200 p-3 rounded mb-4" style="color: rgb(127,98,44);">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ACTION BUTTONS -->
    <div class="flex gap-3 mb-4">

        <a href="{{ route('participants.create') }}"
            class="px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44); hover:brightness(90%);">
            + Add Participant
        </a>

        <button onclick="document.getElementById('uploadModal').showModal()"
            class="px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44); hover:brightness(90%);">
            Upload Excel
        </button>

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
        $('#participantsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
        });
    });
</script>

@endsection