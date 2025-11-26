@extends('layouts.app')

@section('content')
<div class="p-6 rounded-xl shadow-md"
    style="background-color: rgb(255,255,240); color: rgb(127,98,44);">

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-4"
        style="color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0); padding-left: 0.75rem;">
        Upload Participants
    </h2>

    <!-- Upload Form -->
    <form action="{{ route('participants.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold"
                style="color: rgb(127,98,44);">
                Select Excel / CSV / Word File
            </label>

            <input
                type="file"
                name="file"
                required
                style="
                    width:100%;
                    padding: 10px;
                    background-color: rgb(245, 247, 200);
                    border: 2px solid rgb(127,98,44);
                    color: rgb(127,98,44);
                    border-radius: 8px;
                ">
        </div>

        <button
            type="submit"
            style="
                background-color: rgb(203,211,0);
                color: rgb(127,98,44);
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: bold;
                transition: filter 0.2s;
            "
            onmouseover="this.style.filter='brightness(90%)'"
            onmouseout="this.style.filter='brightness(100%)'">
            Upload Participants
        </button>
    </form>

    <p class="mt-4 text-sm" style="color: rgb(127,98,44);">
        File must have columns:
        <strong style="color: rgb(127,98,44);">name, email, phone, organization</strong>
    </p>
</div>
@endsection