@extends('layouts.dashboard')

@section('title', 'CSV Upload Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <a href="{{ route('admin.csv.index') }}" class="btn btn-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back to List
                </a>
            </div>
            <h4 class="page-title">CSV Upload Details</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">File Information</h5>

                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">File Name:</th>
                            <td>{{ $csvUpload->file_name }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($csvUpload->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($csvUpload->status === 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($csvUpload->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($csvUpload->status === 'completed_with_errors')
                                    <span class="badge bg-warning">Completed with Errors</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Uploaded At:</th>
                            <td>{{ $csvUpload->created_at->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $csvUpload->updated_at->format('F d, Y h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($csvUpload->total_rows)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Processing Statistics</h5>

                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ $csvUpload->total_rows }}</h3>
                            <p class="text-muted mb-0">Total Rows</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">{{ $csvUpload->inserted_rows }}</h3>
                            <p class="text-muted mb-0">Inserted</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $csvUpload->updated_rows }}</h3>
                            <p class="text-muted mb-0">Updated</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger">{{ $csvUpload->error_rows }}</h3>
                            <p class="text-muted mb-0">Errors</p>
                        </div>
                    </div>
                </div>

                @if($csvUpload->total_rows > 0)
                <div class="mt-3">
                    <div class="progress" style="height: 25px;">
                        @php
                            $successRate = (($csvUpload->inserted_rows + $csvUpload->updated_rows) / $csvUpload->total_rows) * 100;
                            $errorRate = ($csvUpload->error_rows / $csvUpload->total_rows) * 100;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $successRate }}%" aria-valuenow="{{ $successRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($successRate, 1) }}%
                        </div>
                        @if($errorRate > 0)
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $errorRate }}%" aria-valuenow="{{ $errorRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($errorRate, 1) }}%
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(!empty($errorMessages))
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3 text-danger">
                    <i class="mdi mdi-alert-circle-outline"></i> Error Messages
                </h5>

                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errorMessages as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Actions</h5>

                <div class="d-grid gap-2">
                    @if(in_array($csvUpload->status, ['failed', 'completed_with_errors']))
                        <form action="{{ route('admin.csv.reprocess', $csvUpload) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="mdi mdi-refresh me-1"></i> Reprocess File
                            </button>
                        </form>
                    @endif

                    <a href="{{ asset('storage/csv_uploads/'.$csvUpload->file_name) }}" class="btn btn-primary" download>
                        <i class="mdi mdi-download me-1"></i> Download File
                    </a>

                    <form action="{{ route('admin.csv.destroy', $csvUpload) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="mdi mdi-delete me-1"></i> Delete Record
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Background Processing Info</h5>

                <div class="alert alert-info mb-0">
                    <i class="mdi mdi-information-outline me-1"></i>
                    <strong>Job Queue System:</strong>
                    <p class="mb-0 mt-2 small">
                        CSV files are processed in the background using Laravel's queue system. When a file is uploaded, a job is dispatched to process it asynchronously.
                    </p>
                    <p class="mb-0 mt-2 small">
                        <strong>Status Flow:</strong><br>
                        Pending → Processing → Completed/Failed
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
