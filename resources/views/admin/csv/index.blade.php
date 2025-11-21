@extends('layouts.dashboard')

@section('title', 'Admin - CSV Processing Dashboard')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <a href="{{ route('admin.csv.data') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-database-eye"></i> View CSV Data
                </a>
            </div>
            <h4 class="page-title">CSV Processing Dashboard (Admin)</h4>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-file-multiple widget-icon bg-primary-lighten text-primary"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Total Uploads">Total Uploads</h5>
                <h3 class="mt-3 mb-3">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-clock-outline widget-icon bg-warning-lighten text-warning"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Pending">Pending</h5>
                <h3 class="mt-3 mb-3">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-autorenew widget-icon bg-info-lighten text-info"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Processing">Processing</h5>
                <h3 class="mt-3 mb-3">{{ $stats['processing'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-check-circle widget-icon bg-success-lighten text-success"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Completed">Completed</h5>
                <h3 class="mt-3 mb-3">{{ $stats['completed'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-alert-circle widget-icon bg-danger-lighten text-danger"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Failed">Failed</h5>
                <h3 class="mt-3 mb-3">{{ $stats['failed'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-database widget-icon bg-purple-lighten text-purple"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Total Records">Total Records</h5>
                <h3 class="mt-3 mb-3">{{ number_format($stats['total_records']) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.csv.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="completed_with_errors" {{ request('status') == 'completed_with_errors' ? 'selected' : '' }}>Completed with Errors</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Files List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h4 class="header-title mb-3">CSV Upload Processing Queue</h4>

                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>File Name</th>
                                <th>Status</th>
                                <th>Statistics</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($csvUploads as $upload)
                                <tr>
                                    <td>{{ $upload->id }}</td>
                                    <td>
                                        <i class="mdi mdi-file-delimited text-muted me-1"></i>
                                        {{ $upload->file_name }}
                                    </td>
                                    <td>
                                        @if($upload->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="mdi mdi-clock-outline"></i> Pending
                                            </span>
                                        @elseif($upload->status === 'processing')
                                            <span class="badge bg-info">
                                                <i class="mdi mdi-autorenew"></i> Processing...
                                            </span>
                                        @elseif($upload->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle"></i> Completed
                                            </span>
                                        @elseif($upload->status === 'completed_with_errors')
                                            <span class="badge bg-warning">
                                                <i class="mdi mdi-alert"></i> Partial Success
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="mdi mdi-close-circle"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($upload->total_rows)
                                            <small class="text-muted">
                                                Total: {{ $upload->total_rows }}<br>
                                                <span class="text-success">✓ {{ $upload->inserted_rows }} inserted</span><br>
                                                <span class="text-info">↻ {{ $upload->updated_rows }} updated</span>
                                                @if($upload->error_rows > 0)
                                                    <br><span class="text-danger">✗ {{ $upload->error_rows }} errors</span>
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $upload->created_at->format('M d, Y') }}<br>{{ $upload->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('admin.csv.show', $upload) }}">
                                                    <i class="mdi mdi-eye me-2"></i>View Details
                                                </a>
                                                @if(in_array($upload->status, ['failed', 'completed_with_errors']))
                                                    <form action="{{ route('admin.csv.reprocess', $upload) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="mdi mdi-refresh me-2"></i>Reprocess
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.csv.destroy', $upload) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="mdi mdi-delete me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="mdi mdi-file-upload-outline" style="font-size: 3rem; color: #cbd5e0;"></i>
                                        <p class="text-muted mt-2">No CSV files found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $csvUploads->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
