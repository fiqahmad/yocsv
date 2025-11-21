@extends('layouts.dashboard')

@section('title', 'CSV Data Records')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <a href="{{ route('admin.csv.index') }}" class="btn btn-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <h4 class="page-title">CSV Data Records</h4>
        </div>
    </div>
</div>

<!-- Search -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.csv.data') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by UNIQUE_KEY, Product Title, or Style#" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="mdi mdi-magnify"></i> Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.csv.data') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">
                    Imported CSV Data
                    <small class="text-muted">({{ $csvData->total() }} records)</small>
                </h4>

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>UNIQUE_KEY</th>
                                <th>Product Title</th>
                                <th>Style#</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($csvData as $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td><code>{{ $data->unique_key }}</code></td>
                                    <td>
                                        <strong>{{ $data->product_title }}</strong>
                                        @if($data->product_description)
                                            <br><small class="text-muted">{{ Str::limit($data->product_description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $data->style }}</td>
                                    <td>
                                        @if($data->color_name)
                                            {{ $data->color_name }}
                                            @if($data->sanmar_mainframe_color)
                                                <br><small class="text-muted">{{ $data->sanmar_mainframe_color }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $data->size }}</td>
                                    <td>
                                        @if($data->piece_price)
                                            <span class="badge bg-success">${{ $data->piece_price }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $data->updated_at->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="mdi mdi-database-remove" style="font-size: 3rem; color: #cbd5e0;"></i>
                                        <p class="text-muted mt-2">No data records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $csvData->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
