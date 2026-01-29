@extends('layouts.admin')

@section('title', 'Suggestions')
@section('page-title', 'Parent Suggestions (AI Training Data)')

@section('content')
<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['total'] }}</h3>
                <small>Total Suggestions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3>{{ $stats['pending'] }}</h3>
                <small>Pending Review</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['reviewed'] }}</h3>
                <small>Reviewed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['processed'] }}</h3>
                <small>Processed</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Export -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.suggestions.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search Content</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="school_info" {{ request('category') == 'school_info' ? 'selected' : '' }}>School Info</option>
                    <option value="ranking_feedback" {{ request('category') == 'ranking_feedback' ? 'selected' : '' }}>Ranking Feedback</option>
                    <option value="feature_request" {{ request('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                    <option value="data_correction" {{ request('category') == 'data_correction' ? 'selected' : '' }}>Data Correction</option>
                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.suggestions.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('admin.suggestions.export', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Suggestions List -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Content</th>
                    <th>Kindergarten</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suggestions as $suggestion)
                    <tr>
                        <td>{{ $suggestion->id }}</td>
                        <td>
                            <small>{{ $suggestion->user->email }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ str_replace('_', ' ', $suggestion->category) }}</span>
                        </td>
                        <td>
                            <span title="{{ $suggestion->content }}">{{ Str::limit($suggestion->content, 50) }}</span>
                        </td>
                        <td>
                            @if($suggestion->kindergarten)
                                <a href="{{ route('admin.kindergartens.show', $suggestion->kindergarten) }}" class="text-decoration-none">
                                    {{ Str::limit($suggestion->kindergarten->name_en, 20) }}
                                </a>
                            @else
                                <span class="text-muted">General</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ 
                                $suggestion->status === 'pending' ? 'bg-warning text-dark' : 
                                ($suggestion->status === 'reviewed' ? 'bg-info' : 
                                ($suggestion->status === 'processed' ? 'bg-success' : 'bg-secondary')) 
                            }}">
                                {{ $suggestion->status }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $suggestion->created_at->format('Y-m-d') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No suggestions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $suggestions->links() }}
</div>
@endsection
