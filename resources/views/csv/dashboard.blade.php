@extends('layouts.dashboard')

@section('title', 'CSV File Manager')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">CSV File Manager</h4>
        </div>
    </div>
</div>

<!-- Upload Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('csv.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label for="csv_file" class="form-label fw-bold">Upload CSV File</label>
                        <input type="file" class="form-control @error('csv_file') is-invalid @enderror" name="csv_file" id="csv_file" accept=".csv,.txt" required>
                        @error('csv_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum 10MB. Formats: .csv, .txt | Idempotent uploads using UNIQUE_KEY</small>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-upload me-1"></i> Upload & Process
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info mb-0" role="alert">
                            <strong><i class="mdi mdi-information-outline"></i> Required Columns:</strong>
                            UNIQUE_KEY, PRODUCT_TITLE, PRODUCT_DESCRIPTION, STYLE#, SANMAR_MAINFRAME_COLOR, SIZE, COLOR_NAME, PIECE_PRICE
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- File List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Left Sidebar -->
                <div class="page-aside-left">
                    <div class="email-menu-list">
                        <a href="{{ route('csv.index') }}" class="list-group-item border-0">
                            <i class="mdi mdi-file-document-multiple font-18 align-middle me-2"></i>All Files
                        </a>
                        <a href="{{ route('csv.index') }}?status=pending" class="list-group-item border-0">
                            <i class="mdi mdi-clock-outline font-18 align-middle me-2"></i>Pending
                        </a>
                        <a href="{{ route('csv.index') }}?status=completed" class="list-group-item border-0">
                            <i class="mdi mdi-check-circle-outline font-18 align-middle me-2"></i>Completed
                        </a>
                    </div>

                    <div class="mt-5">
                        <h6 class="text-uppercase">Storage</h6>
                        <div class="progress my-2 progress-sm">
                            <div class="progress-bar progress-lg bg-success" role="progressbar" style="width: {{ min(($csvUploads->total() * 5), 100) }}%"
                                aria-valuenow="{{ min(($csvUploads->total() * 5), 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted font-12 mb-0">{{ $csvUploads->total() }} files uploaded</p>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="page-aside-right">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($csvUploads->count() > 0)
                        <div class="mt-3">
                            <h5 class="mb-3">Recent Files</h5>

                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">File Name</th>
                                            <th class="border-0">Uploaded At</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0" style="width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($csvUploads as $upload)
                                            <tr>
                                                <td>
                                                    <i class="mdi mdi-file-delimited text-muted font-18 me-2"></i>
                                                    <span class="fw-semibold">{{ $upload->file_name }}</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">{{ $upload->created_at->format('M d, Y') }}</p>
                                                    <span class="font-12 text-muted">{{ $upload->created_at->format('h:i A') }}</span>
                                                </td>
                                                <td>
                                                    @if($upload->status === 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($upload->status === 'processing')
                                                        <span class="badge bg-info">Processing...</span>
                                                    @elseif($upload->status === 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @if($upload->total_rows)
                                                            <br><small class="text-muted">{{ $upload->inserted_rows }} inserted, {{ $upload->updated_rows }} updated</small>
                                                        @endif
                                                    @elseif($upload->status === 'completed_with_errors')
                                                        <span class="badge bg-warning">Completed ({{ $upload->error_rows }} errors)</span>
                                                    @else
                                                        <span class="badge bg-danger">Failed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group dropdown">
                                                        <a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ asset('storage/csv_uploads/'.$upload->file_name) }}" download>
                                                                <i class="mdi mdi-download me-2 text-muted vertical-middle"></i>Download
                                                            </a>
                                                            <form action="{{ route('csv.destroy', $upload) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $csvUploads->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-file-document-outline" style="font-size: 4rem; color: #cbd5e0;"></i>
                            <h5 class="mt-3 text-muted">No CSV files uploaded yet</h5>
                            <p class="text-muted">Use the upload form above to get started</p>
                        </div>
                    @endif

                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@endsection
