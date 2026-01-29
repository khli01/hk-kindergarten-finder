@extends('layouts.admin')

@section('title', 'Deadlines')
@section('page-title', 'Registration Deadlines Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted">{{ $deadlines->total() }} deadline(s)</span>
    <a href="{{ route('admin.deadlines.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Deadline
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.deadlines.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="kindergarten" class="form-select">
                    <option value="">All Kindergartens</option>
                    @foreach($kindergartens as $k)
                        <option value="{{ $k->id }}" {{ request('kindergarten') == $k->id ? 'selected' : '' }}>
                            {{ $k->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="event_type" class="form-select">
                    <option value="">All Events</option>
                    <option value="application_start" {{ request('event_type') == 'application_start' ? 'selected' : '' }}>Application Start</option>
                    <option value="application_deadline" {{ request('event_type') == 'application_deadline' ? 'selected' : '' }}>Application Deadline</option>
                    <option value="interview" {{ request('event_type') == 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="open_day" {{ request('event_type') == 'open_day' ? 'selected' : '' }}>Open Day</option>
                    <option value="briefing_session" {{ request('event_type') == 'briefing_session' ? 'selected' : '' }}>Briefing Session</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="verified" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input type="checkbox" name="upcoming" value="1" class="form-check-input" {{ request('upcoming') ? 'checked' : '' }}>
                    <label class="form-check-label">Upcoming Only</label>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.deadlines.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Deadlines Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kindergarten</th>
                    <th>Event Type</th>
                    <th>Date</th>
                    <th>Academic Year</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deadlines as $deadline)
                    <tr>
                        <td>
                            <a href="{{ route('admin.kindergartens.show', $deadline->kindergarten) }}" class="text-decoration-none">
                                {{ $deadline->kindergarten->name_en }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $deadline->localized_event_type }}</span>
                        </td>
                        <td>
                            {{ $deadline->deadline_date->format('Y-m-d') }}
                            @if($deadline->deadline_time)
                                <small class="text-muted">{{ $deadline->deadline_time->format('H:i') }}</small>
                            @endif
                        </td>
                        <td>{{ $deadline->academic_year }}</td>
                        <td>
                            @if($deadline->is_scraped)
                                <span class="badge bg-secondary">Scraped</span>
                            @else
                                <span class="badge bg-primary">Manual</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.deadlines.verify', $deadline) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $deadline->is_verified ? 'btn-success' : 'btn-outline-warning' }}">
                                    <i class="bi bi-{{ $deadline->is_verified ? 'check-circle-fill' : 'question-circle' }}"></i>
                                    {{ $deadline->is_verified ? 'Verified' : 'Verify' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.deadlines.edit', $deadline) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.deadlines.destroy', $deadline) }}" method="POST" 
                                      onsubmit="return confirm('Delete this deadline?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No deadlines found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $deadlines->links() }}
</div>
@endsection
