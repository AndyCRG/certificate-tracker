@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4" style="color: rgb(127,98,44);">Add Certificate</h2>

    <form action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Course ID -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Course ID</label>
            <input type="text" name="course_id" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);"
                required>
        </div>

        <!-- Course Name -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Course Name</label>
            <input type="text" name="course_name" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);"
                required>
        </div>
        <!-- Certificate Name -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Certificate Name</label>
            <input type="text" name="certificate_name" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);"
                required>
        </div>
        <!-- Issued By -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Issued By</label>
            <input type="text" name="issued_by" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);"
                required>
        </div>

        <!-- Issue Date -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Date of Issue</label>
            <input type="date" name="issue_date" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);"
                required>
        </div>

        <!-- Certificate File -->
        <!-- <div class="mb-4">
            <label class="block mb-1 font-semibold" style="color: rgb(127,98,44);">Certificate File</label>
            <input type="file" name="certificate_file" class="w-full px-3 py-2 border rounded-lg"
                style="border-color: rgb(203,211,0); background-color: rgb(245,247,200); color: rgb(127,98,44);">
        </div> -->

        <button type="submit"
            class="px-4 py-2 rounded-lg font-semibold mr-2"
            style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
            Save
        </button>

        <a href="{{ route('certificates.index') }}"
            class="px-4 py-2 rounded-lg font-semibold"
            style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
            Cancel
        </a>
    </form>
</div>
@endsection