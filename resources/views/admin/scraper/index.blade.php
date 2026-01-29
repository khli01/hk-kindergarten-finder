@extends('layouts.admin')

@section('title', 'Deadline Scraper')
@section('page-title', 'Deadline Scraper Management')

@section('content')
<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['total_configs'] }}</h3>
                <small>Total Configs</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['active_configs'] }}</h3>
                <small>Active</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3>{{ $stats['needs_scraping'] }}</h3>
                <small>Needs Update</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['configs_with_errors'] }}</h3>
                <small>With Errors</small>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <form action="{{ route('admin.scraper.run') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="hours" value="24">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Run scraper for all configurations?')">
                <i class="bi bi-play-fill me-1"></i> Run All Scrapers
            </button>
        </form>
        <a href="{{ route('admin.scraper.configs.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Add Config
        </a>
    </div>
    @if($kindergartensWithoutConfig > 0)
        <span class="badge bg-info">{{ $kindergartensWithoutConfig }} kindergartens without scraper config</span>
    @endif
</div>

<!-- Configs Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kindergarten</th>
                    <th>Target URL</th>
                    <th>Last Scraped</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($configs as $config)
                    <tr class="{{ $config->last_error ? 'table-warning' : '' }}">
                        <td>
                            <a href="{{ route('admin.kindergartens.show', $config->kindergarten) }}" class="text-decoration-none">
                                {{ $config->kindergarten->name_en }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ $config->target_url }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($config->target_url, 40) }}
                                <i class="bi bi-box-arrow-up-right small"></i>
                            </a>
                        </td>
                        <td>
                            @if($config->last_scraped_at)
                                <small class="{{ $config->last_scraped_at->diffInHours(now()) > 24 ? 'text-warning' : 'text-muted' }}">
                                    {{ $config->last_scraped_at->diffForHumans() }}
                                </small>
                            @else
                                <span class="badge bg-secondary">Never</span>
                            @endif
                        </td>
                        <td>
                            @if($config->last_error)
                                <span class="badge bg-danger" title="{{ $config->last_error }}">Error</span>
                            @elseif($config->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <form action="{{ route('admin.scraper.run.single', $config->kindergarten) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" title="Run Scraper">
                                        <i class="bi bi-play"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.scraper.configs.edit', $config) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.scraper.configs.destroy', $config) }}" method="POST" 
                                      onsubmit="return confirm('Delete this config?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($config->last_error)
                        <tr class="table-warning">
                            <td colspan="5">
                                <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>{{ $config->last_error }}</small>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No scraper configurations yet. 
                            <a href="{{ route('admin.scraper.configs.create') }}">Add one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $configs->links() }}
</div>

<!-- Instructions -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Scraper Instructions</h5>
    </div>
    <div class="card-body">
        <p>The scraper automatically searches for registration deadline information on kindergarten websites. It looks for:</p>
        <ul>
            <li>Common deadline keywords (報名, 申請, 截止, 面試, 開放日, 簡介會)</li>
            <li>Date patterns in various formats (2024年3月1日, 2024-03-01, etc.)</li>
        </ul>
        <p>You can also provide a custom CSS selector to target specific elements on the page.</p>
        <p class="mb-0"><strong>Note:</strong> The scraper runs automatically every 24 hours via scheduled task. You can also trigger it manually from this page.</p>
    </div>
</div>
@endsection
