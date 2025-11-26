@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Certificate</h1>

    <form action="{{ route('certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Certificate Name</label>
            <input type="text" name="certificate_name" value="{{ $certificate->certificate_name }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Issued By</label>
            <input type="text" name="issued_by" value="{{ $certificate->issued_by }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Issue Date</label>
            <input type="date" name="issue_date" value="{{ $certificate->issue_date }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" value="{{ $certificate->expiry_date }}" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Certificate File</label>
            <input type="file" name="certificate_file" class="form-control">
            @if($certificate->certificate_file)
            <small>Current file: <a href="{{ asset('storage/'.$certificate->certificate_file) }}" target="_blank">View</a></small>
            @endif
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('certificates.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection