@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4" style="color: rgb(127,98,44);">Certificates</h2>
    <a href="{{ route('certificates.create') }}"
        class="px-4 py-2 rounded-lg font-semibold mb-4 inline-block"
        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
        Add Certificate
    </a>

    @if(session('success'))
    <div class="px-4 py-2 mb-4 rounded-lg font-semibold"
        style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full border-collapse">
        <thead style="background-color: rgb(245,247,200); border-bottom: 3px solid rgb(203,211,0);">
            <tr style="color: rgb(127,98,44); font-weight: 600;">
                <th class="px-3 py-2">#</th>
                <th class="px-3 py-2">Course ID</th>
                <th class="px-3 py-2">Course Name</th>
                <th class="px-3 py-2">Certificate Name</th>
                <th class="px-3 py-2">Date of Issue</th>
                <th class="px-3 py-2">Total Participants</th>
                <th class="px-3 py-2">Total Collected</th>
                <th class="px-3 py-2">Total Not Collected</th>
                <th class="px-3 py-2">File</th>
                <th class="px-3 py-2">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($certificates as $certificate)
            <tr class="hover:bg-[rgb(203,211,0)]/20">
                <td class="px-3 py-2" style="color: rgb(127,98,44);">{{ $certificate->id }}</td>

                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->course_id }}
                </td>

                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->course_name }}
                </td>

                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->certificate_name }}
                </td>

                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ \Carbon\Carbon::parse($certificate->issue_date)->format('d-M-Y') }}
                </td>

                <!-- Total participants -->
                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->participants_count }}
                </td>

                <!-- Total collected -->
                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->collected_count }}
                </td>

                <!-- Total not collected -->
                <td class="px-3 py-2" style="color: rgb(127,98,44);">
                    {{ $certificate->not_collected_count }}
                </td>

                <!-- File -->
                <td class="px-3 py-2">
                    @if($certificate->certificate_file)
                    <a href="{{ asset('storage/'.$certificate->certificate_file) }}"
                        target="_blank"
                        class="px-3 py-1 rounded-lg font-semibold"
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44);">
                        View
                    </a>
                    @else
                    <span class="px-3 py-1 rounded-lg font-semibold"
                        style="background-color: rgb(245,247,200); color: rgb(127,98,44); border-left: 4px solid rgb(203,211,0);">
                        -
                    </span>
                    @endif
                </td>

                <!-- Actions -->
                <td class="px-3 py-2">
                    <a href="{{ route('certificates.edit', $certificate->id) }}"
                        class="px-3 py-1 rounded-lg font-semibold"
                        style="background-color: rgb(203,211,0); color: rgb(127,98,44); margin-right: 4px;">
                        Edit
                    </a>

                    <form action="{{ route('certificates.destroy', $certificate->id) }}"
                        method="POST"
                        style="display:inline;">
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
</div>
@endsection