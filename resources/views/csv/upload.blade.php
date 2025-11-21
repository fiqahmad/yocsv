@extends('layouts.dashboard')

@section('title', 'Upload CSV File')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('csv.index') }}">CSV Files</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </div>
            <h4 class="page-title">Upload CSV File</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('csv.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv,.txt" required>
                        @error('csv_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum file size: 10MB. Accepted formats: .csv, .txt</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('csv.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-upload me-1"></i> Upload File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
