@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md" style="color: rgb(127,98,44);">

    <h2 class="text-2xl font-bold mb-6" style="color: rgb(127,98,44);">Manage Participants</h2>

    {{-- ========================= --}}
    {{-- SEARCH PARTICIPANT --}}
    {{-- ========================= --}}
    <div class="mb-8 p-4 rounded-lg" style="background-color: #f3f3f3; color: rgb(127,98,44);">
        <h3 class="text-lg font-semibold mb-3" style="color: rgb(127,98,44);">Search Participant</h3>

        <form action="{{ route('participants.index') }}" method="GET" class="flex gap-3">
            <input
                type="text"
                name="search"
                placeholder="Search by name, email, or phone..."
                class="border rounded-lg w-full p-2"
                style="color: rgb(127,98,44); background-color: white;">
            <button
                type="submit"
                class="px-4 py-2 rounded-lg hover:brightness-90"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                Search
            </button>
        </form>
    </div>

    {{-- ========================= --}}
    {{-- ADD SINGLE PARTICIPANT --}}
    {{-- ========================= --}}
    <div class="mb-10 p-4 rounded-lg border" style="background-color: #ebf8ff; border-color: #bfdbfe; color: rgb(127,98,44);">
        <h3 class="text-lg font-semibold mb-3" style="color: rgb(127,98,44);">Add Single Participant</h3>

        <form action="{{ route('participants.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-normal" style="color: rgb(127,98,44);">Name</label>
                    <input type="text" name="name" class="border rounded-lg w-full p-2"
                        style="color: rgb(127,98,44); background-color: white;" required>
                </div>

                <div>
                    <label class="block mb-1 font-normal" style="color: rgb(127,98,44);">Email</label>
                    <input type="email" name="email" class="border rounded-lg w-full p-2"
                        style="color: rgb(127,98,44); background-color: white;" required>
                </div>

                <div>
                    <label class="block mb-1 font-normal" style="color: rgb(127,98,44);">Phone</label>
                    <input type="text" name="phone" class="border rounded-lg w-full p-2"
                        style="color: rgb(127,98,44); background-color: white;" required>
                </div>

                <div>
                    <label class="block mb-1 font-normal" style="color: rgb(127,98,44);">Organization</label>
                    <input type="text" name="organization" class="border rounded-lg w-full p-2"
                        style="color: rgb(127,98,44); background-color: white;">
                </div>

                <!-- New Course Field -->
                <div>
                    <label class="block mb-1 font-normal" style="color: rgb(127,98,44);">Course</label>
                    <input type="text" name="course" class="border rounded-lg w-full p-2"
                        style="color: rgb(127,98,44); background-color: white;" required>
                </div>
            </div>

            <button
                type="submit"
                class="mt-4 px-4 py-2 rounded-lg hover:brightness-90"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                Add Participant
            </button>
        </form>
    </div>

    {{-- ========================= --}}
    {{-- BULK UPLOAD --}}
    {{-- ========================= --}}
    <div class="p-4 rounded-lg border" style="background-color: #f0fdf4; border-color: #bbf7d0; color: rgb(127,98,44);">
        <h3 class="text-lg font-semibold mb-3" style="color: rgb(127,98,44);">Bulk Upload Participants</h3>

        <form action="{{ route('participants.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input
                    type="file"
                    name="file"
                    accept=".xlsx,.csv,.txt"
                    class="border rounded-lg w-full p-2"
                    style="color: rgb(127,98,44); background-color: white;"
                    required>
            </div>

            <button
                type="submit"
                class="px-4 py-2 rounded-lg hover:brightness-90"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                Upload File
            </button>
        </form>

        {{-- UNDO BULK UPLOAD --}}
        <form action="{{ route('participants.undoLastUpload') }}" method="POST" class="mt-4">
            @csrf
            <button
                type="submit"
                class="px-4 py-2 rounded-lg hover:brightness-90"
                style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                Undo Last Upload
            </button>
        </form>
    </div>

</div>
@endsection