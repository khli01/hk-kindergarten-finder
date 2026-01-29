@extends('layouts.admin')

@section('title', $kindergarten->name_en)
@section('page-title', 'Kindergarten Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.kindergartens.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
    <div class="btn-group">
        <a href="{{ route('admin.kindergartens.edit', $kindergarten) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('kindergartens.show', $kindergarten) }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-eye me-1"></i> View on Site
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Basic Info -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Basic Information</h5>
                <span class="badge {{ $kindergarten->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $kindergarten->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Name (Traditional Chinese)</label>
                        <p class="mb-0 fw-bold">{{ $kindergarten->name_zh_tw }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Name (Simplified Chinese)</label>
                        <p class="mb-0">{{ $kindergarten->name_zh_cn }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Name (English)</label>
                        <p class="mb-0">{{ $kindergarten->name_en }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">District</label>
                        <p class="mb-0">{{ $kindergarten->district->name_en }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">School Type</label>
                        <p class="mb-0">{{ ucfirst(str_replace('_', '-', $kindergarten->school_type ?? 'N/A')) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Established Year</label>
                        <p class="mb-0">{{ $kindergarten->established_year ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">School Features</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Feature
                </button>
            </div>
            <div class="card-body">
                @if($kindergarten->features->count() > 0)
                    @foreach($kindergarten->features->groupBy('feature_type') as $type => $features)
                        <div class="mb-3">
                            <h6 class="text-muted">{{ ucfirst(str_replace('_', ' ', $type)) }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($features as $feature)
                                    <span class="badge bg-light text-dark border">
                                        {{ $feature->value_en }}
                                        <form action="{{ route('admin.kindergartens.features.destroy', [$kindergarten, $feature]) }}" 
                                              method="POST" class="d-inline" onsubmit="return confirm('Remove this feature?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-close btn-sm ms-1" style="font-size: 0.6rem;"></button>
                                        </form>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">No features added yet.</p>
                @endif
            </div>
        </div>

        <!-- Deadlines -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Registration Deadlines</h5>
            </div>
            <div class="card-body">
                @if($kindergarten->deadlines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kindergarten->deadlines as $deadline)
                                    <tr>
                                        <td>{{ $deadline->localized_event_type }}</td>
                                        <td>{{ $deadline->deadline_date->format('Y-m-d') }}</td>
                                        <td>{{ $deadline->academic_year }}</td>
                                        <td>
                                            @if($deadline->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Unverified</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No deadlines recorded.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stats -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Ranking Score</label>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" style="width: {{ $kindergarten->ranking_score }}%"></div>
                        </div>
                        <strong>{{ $kindergarten->ranking_score }}/100</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Primary Success Rate</label>
                    <p class="mb-0 fw-bold text-success">{{ $kindergarten->primary_success_rate ?? 'N/A' }}%</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Available Classes</label>
                    <p class="mb-0">
                        @foreach($kindergarten->available_classes as $class)
                            <span class="badge bg-primary">{{ $class }}</span>
                        @endforeach
                    </p>
                </div>
                <div>
                    <label class="text-muted small">Monthly Fee</label>
                    <p class="mb-0">{{ $kindergarten->fee_range ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2 text-primary"></i>{{ $kindergarten->address_en }}
                    </li>
                    @if($kindergarten->phone)
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2 text-primary"></i>{{ $kindergarten->phone }}
                        </li>
                    @endif
                    @if($kindergarten->email)
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2 text-primary"></i>{{ $kindergarten->email }}
                        </li>
                    @endif
                    @if($kindergarten->website_url)
                        <li class="mb-2">
                            <i class="bi bi-globe me-2 text-primary"></i>
                            <a href="{{ $kindergarten->website_url }}" target="_blank">{{ $kindergarten->website_url }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Suggestions -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Suggestions ({{ $kindergarten->suggestions->count() }})</h5>
            </div>
            @if($kindergarten->suggestions->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($kindergarten->suggestions->take(5) as $suggestion)
                        <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="list-group-item list-group-item-action">
                            <small class="text-muted">{{ $suggestion->created_at->format('Y-m-d') }}</small>
                            <p class="mb-0 small">{{ Str::limit($suggestion->content, 60) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="card-body text-muted">No suggestions</div>
            @endif
        </div>
    </div>
</div>

<!-- Add Feature Modal -->
<div class="modal fade" id="addFeatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kindergartens.features.store', $kindergarten) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Feature Type</label>
                        <select name="feature_type" class="form-select" required>
                            <option value="teaching_method">Teaching Method</option>
                            <option value="language">Teaching Language</option>
                            <option value="curriculum">Curriculum</option>
                            <option value="facility">Facility</option>
                            <option value="extracurricular">Extracurricular</option>
                            <option value="award">Award</option>
                            <option value="strength">Strength</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value (Traditional Chinese)</label>
                        <input type="text" name="value_zh_tw" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value (Simplified Chinese)</label>
                        <input type="text" name="value_zh_cn" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value (English)</label>
                        <input type="text" name="value_en" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
