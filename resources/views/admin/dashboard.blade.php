@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary text-white me-3">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['active_kindergartens'] }}</div>
                    <div class="text-muted">Active Schools</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success text-white me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['verified_users'] }}</div>
                    <div class="text-muted">Verified Users</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-warning text-white me-3">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['upcoming_deadlines'] }}</div>
                    <div class="text-muted">Upcoming Deadlines</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-danger text-white me-3">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['pending_suggestions'] }}</div>
                    <div class="text-muted">Pending Suggestions</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Suggestions -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Suggestions</h5>
                <a href="{{ route('admin.suggestions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @if($recentSuggestions->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentSuggestions as $suggestion)
                        <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-secondary me-2">{{ $suggestion->category }}</span>
                                    <small class="text-muted">{{ $suggestion->user->email }}</small>
                                    <p class="mb-1 small mt-1">{{ Str::limit($suggestion->content, 80) }}</p>
                                </div>
                                <span class="badge {{ $suggestion->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ $suggestion->status }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="card-body text-center text-muted">
                    No suggestions yet
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Kindergartens -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Kindergartens</h5>
                <a href="{{ route('admin.kindergartens.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @if($recentKindergartens->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentKindergartens as $k)
                        <a href="{{ route('admin.kindergartens.show', $k) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $k->name_en }}</h6>
                                    <small class="text-muted">{{ $k->district->name_en }}</small>
                                </div>
                                <span class="badge {{ $k->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $k->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="card-body text-center text-muted">
                    No kindergartens yet
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Suggestions by Category</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($suggestionsByCategory as $category => $count)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $category)) }}</td>
                                    <td class="text-end"><strong>{{ $count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.kindergartens.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add Kindergarten
                    </a>
                    <a href="{{ route('admin.import.index') }}" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i> Import Data
                    </a>
                    <a href="{{ route('admin.suggestions.export') }}" class="btn btn-info">
                        <i class="bi bi-download me-1"></i> Export Suggestions
                    </a>
                    <a href="{{ route('admin.scraper.index') }}" class="btn btn-warning">
                        <i class="bi bi-cloud-download me-1"></i> Run Scraper
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
