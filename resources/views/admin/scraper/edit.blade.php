@extends('layouts.admin')

@section('title', 'Edit Scraper Config')
@section('page-title', 'Edit Scraper Configuration')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <strong>Kindergarten:</strong> {{ $config->kindergarten->name_en }}
                </div>

                <form action="{{ route('admin.scraper.configs.update', $config) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Target URL *</label>
                        <input type="url" name="target_url" class="form-control @error('target_url') is-invalid @enderror" 
                               value="{{ old('target_url', $config->target_url) }}" required>
                        @error('target_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CSS Selector (Optional)</label>
                        <input type="text" name="deadline_selector" class="form-control" 
                               value="{{ old('deadline_selector', $config->deadline_selector) }}">
                        <small class="text-muted">Leave empty to search the entire page.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date Format</label>
                        <input type="text" name="date_format" class="form-control" 
                               value="{{ old('date_format', $config->date_format) }}">
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                                   {{ old('is_active', $config->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                    @if($config->last_error)
                        <div class="alert alert-danger mb-4">
                            <strong>Last Error:</strong> {{ $config->last_error }}
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Config
                        </button>
                        <a href="{{ route('admin.scraper.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
