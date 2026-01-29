@extends('layouts.admin')

@section('title', 'Import/Export')
@section('page-title', 'Data Import & Export')

@section('content')
@if(session('import_errors'))
    <div class="alert alert-warning">
        <h6>Import Warnings:</h6>
        <ul class="mb-0">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <!-- Import Section -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Import Data</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.import.kindergartens') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Upload CSV File</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" 
                               accept=".csv,.txt" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum file size: 10MB. Supports CSV format with UTF-8 encoding.</small>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-1"></i> Expected CSV Format:</h6>
                        <small>
                            name_zh_tw, name_zh_cn, name_en, district_name_en, address_zh_tw, address_zh_cn, 
                            address_en, website_url, has_pn, has_k1, has_k2, has_k3, primary_success_rate, 
                            ranking_score, phone, email, school_type
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Import Kindergartens
                        </button>
                        <a href="{{ route('admin.import.template') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-download me-2"></i>Export Data</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export your data to CSV format for backup or analysis.</p>

                <div class="d-grid gap-3">
                    <a href="{{ route('admin.export.kindergartens') }}" class="btn btn-success">
                        <i class="bi bi-building me-2"></i> Export All Kindergartens
                    </a>
                    <a href="{{ route('admin.suggestions.export') }}" class="btn btn-info">
                        <i class="bi bi-chat-dots me-2"></i> Export All Suggestions
                    </a>
                </div>

                <hr class="my-4">

                <h6>Export Statistics</h6>
                <ul class="list-unstyled text-muted">
                    <li><i class="bi bi-building me-2"></i>{{ \App\Models\Kindergarten::count() }} Kindergartens</li>
                    <li><i class="bi bi-chat-dots me-2"></i>{{ \App\Models\Suggestion::count() }} Suggestions</li>
                    <li><i class="bi bi-calendar-event me-2"></i>{{ \App\Models\RegistrationDeadline::count() }} Deadlines</li>
                    <li><i class="bi bi-people me-2"></i>{{ \App\Models\User::count() }} Users</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Import Instructions -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Import Instructions</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>District Names (use exactly as shown)</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Hong Kong Island:</strong>
                        <ul class="small">
                            <li>Central and Western</li>
                            <li>Wan Chai</li>
                            <li>Eastern</li>
                            <li>Southern</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <strong>Kowloon:</strong>
                        <ul class="small">
                            <li>Yau Tsim Mong</li>
                            <li>Sham Shui Po</li>
                            <li>Kowloon City</li>
                            <li>Wong Tai Sin</li>
                            <li>Kwun Tong</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <strong>New Territories:</strong>
                        <ul class="small">
                            <li>Kwai Tsing</li>
                            <li>Tsuen Wan</li>
                            <li>Tuen Mun</li>
                            <li>Yuen Long</li>
                            <li>North</li>
                            <li>Tai Po</li>
                            <li>Sha Tin</li>
                            <li>Sai Kung</li>
                            <li>Islands</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Column Values</h6>
                <ul class="small">
                    <li><strong>has_pn, has_k1, has_k2, has_k3:</strong> "Yes" or "No"</li>
                    <li><strong>primary_success_rate:</strong> Number 0-100 (e.g., 85.5)</li>
                    <li><strong>ranking_score:</strong> Integer 0-100</li>
                    <li><strong>school_type:</strong> "private", "non_profit", or "government"</li>
                    <li><strong>website_url:</strong> Full URL including https://</li>
                </ul>

                <h6 class="mt-3">Tips</h6>
                <ul class="small text-muted">
                    <li>Save your Excel file as "CSV UTF-8 (Comma delimited)"</li>
                    <li>Existing records will be updated based on English name + district match</li>
                    <li>Empty cells are allowed for optional fields</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
