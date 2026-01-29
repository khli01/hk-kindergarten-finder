@extends('layouts.admin')

@section('title', 'Add Scraper Config')
@section('page-title', 'Add Scraper Configuration')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.scraper.configs.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kindergarten *</label>
                        <select name="kindergarten_id" class="form-select @error('kindergarten_id') is-invalid @enderror" required>
                            <option value="">Select Kindergarten</option>
                            @foreach($kindergartens as $k)
                                <option value="{{ $k->id }}" {{ old('kindergarten_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name_en }} ({{ $k->website_url }})
                                </option>
                            @endforeach
                        </select>
                        @error('kindergarten_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Only kindergartens with a website URL and no existing config are shown.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target URL *</label>
                        <input type="url" name="target_url" class="form-control @error('target_url') is-invalid @enderror" 
                               value="{{ old('target_url') }}" placeholder="https://school.edu.hk/admission" required>
                        @error('target_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">The specific page where admission/deadline information is found.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CSS Selector (Optional)</label>
                        <input type="text" name="deadline_selector" class="form-control" 
                               value="{{ old('deadline_selector') }}" placeholder="e.g., .admission-info, #deadline-section">
                        <small class="text-muted">Leave empty to search the entire page, or specify a CSS selector to target specific elements.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date Format</label>
                        <input type="text" name="date_format" class="form-control" 
                               value="{{ old('date_format', 'Y-m-d') }}" placeholder="Y-m-d">
                        <small class="text-muted">Expected date format on the page (default: Y-m-d). The scraper auto-detects common formats.</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                            <label class="form-check-label">Active (include in scheduled scraping)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Create Config
                        </button>
                        <a href="{{ route('admin.scraper.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
