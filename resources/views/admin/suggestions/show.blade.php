@extends('layouts.admin')

@section('title', 'View Suggestion')
@section('page-title', 'Suggestion Details')

@section('content')
<a href="{{ route('admin.suggestions.index') }}" class="btn btn-outline-secondary mb-4">
    <i class="bi bi-arrow-left me-1"></i> Back to List
</a>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Suggestion #{{ $suggestion->id }}</h5>
                <span class="badge {{ 
                    $suggestion->status === 'pending' ? 'bg-warning text-dark' : 
                    ($suggestion->status === 'reviewed' ? 'bg-info' : 
                    ($suggestion->status === 'processed' ? 'bg-success' : 'bg-secondary')) 
                }}">
                    {{ ucfirst($suggestion->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small">User</label>
                        <p class="mb-0">{{ $suggestion->user->name }} ({{ $suggestion->user->email }})</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Submitted</label>
                        <p class="mb-0">{{ $suggestion->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small">Category</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $suggestion->category)) }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Related Kindergarten</label>
                        <p class="mb-0">
                            @if($suggestion->kindergarten)
                                <a href="{{ route('admin.kindergartens.show', $suggestion->kindergarten) }}">
                                    {{ $suggestion->kindergarten->name_en }}
                                </a>
                            @else
                                <span class="text-muted">General Feedback</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-muted small">Content</label>
                    <div class="p-3 bg-light rounded">
                        {{ $suggestion->content }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Admin Notes (Internal)</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.suggestions.notes', $suggestion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea name="admin_notes" class="form-control" rows="4" 
                                  placeholder="Add internal notes about this suggestion...">{{ $suggestion->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Notes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.suggestions.status', $suggestion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="pending" {{ $suggestion->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $suggestion->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="processed" {{ $suggestion->status == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="archived" {{ $suggestion->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.suggestions.status', $suggestion) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="reviewed">
                        <button type="submit" class="btn btn-info w-100" {{ $suggestion->status !== 'pending' ? 'disabled' : '' }}>
                            <i class="bi bi-check me-1"></i> Mark as Reviewed
                        </button>
                    </form>
                    <form action="{{ route('admin.suggestions.status', $suggestion) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="processed">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-all me-1"></i> Mark as Processed
                        </button>
                    </form>
                    <form action="{{ route('admin.suggestions.status', $suggestion) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="archived">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-archive me-1"></i> Archive
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
