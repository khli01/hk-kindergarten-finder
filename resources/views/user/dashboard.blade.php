@extends('layouts.app')

@section('title', __('messages.dashboard') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">{{ __('messages.dashboard') }}</h1>
            <p class="text-muted">{{ __('messages.welcome') }}, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $stats['favorites_count'] }}</h3>
                            <p class="mb-0">{{ __('messages.favorites') }}</p>
                        </div>
                        <i class="bi bi-heart-fill display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('favorites.index') }}" class="text-white text-decoration-none">
                        {{ __('messages.view_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $stats['suggestions_count'] }}</h3>
                            <p class="mb-0">{{ __('messages.my_suggestions') }}</p>
                        </div>
                        <i class="bi bi-chat-dots-fill display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('suggestions.index') }}" class="text-white text-decoration-none">
                        {{ __('messages.view_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Favorites -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-heart me-2 text-danger"></i>{{ __('messages.favorites') }}</h5>
                    <a href="{{ route('favorites.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                </div>
                @if($favorites->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($favorites as $favorite)
                            <a href="{{ route('kindergartens.show', $favorite->kindergarten) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $favorite->kindergarten->localized_name }}</h6>
                                        <small class="text-muted">{{ $favorite->kindergarten->district->localized_name }}</small>
                                    </div>
                                    @if($favorite->kindergarten->ranking_score > 0)
                                        <span class="badge bg-warning text-dark">{{ $favorite->kindergarten->ranking_score }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <i class="bi bi-heart display-3 text-muted"></i>
                        <p class="text-muted mt-3">You haven't added any favorites yet.</p>
                        <a href="{{ route('kindergartens.index') }}" class="btn btn-primary">
                            {{ __('messages.start_search') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Suggestions -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-chat-dots me-2 text-success"></i>{{ __('messages.my_suggestions') }}</h5>
                    <a href="{{ route('suggestions.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus"></i> {{ __('messages.submit_suggestion') }}
                    </a>
                </div>
                @if($suggestions->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($suggestions as $suggestion)
                            <a href="{{ route('suggestions.show', $suggestion) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary mb-1">{{ $suggestion->localized_category }}</span>
                                        <p class="mb-1 small">{{ Str::limit($suggestion->content, 60) }}</p>
                                        <small class="text-muted">{{ $suggestion->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge {{ $suggestion->status === 'pending' ? 'bg-warning text-dark' : ($suggestion->status === 'processed' ? 'bg-success' : 'bg-info') }}">
                                        {{ $suggestion->localized_status }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <i class="bi bi-chat-dots display-3 text-muted"></i>
                        <p class="text-muted mt-3">You haven't submitted any suggestions yet.</p>
                        <a href="{{ route('suggestions.create') }}" class="btn btn-success">
                            {{ __('messages.submit_suggestion') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Links</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('kindergartens.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-search me-1"></i>{{ __('messages.kindergartens') }}
                        </a>
                        <a href="{{ route('deadlines.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-event me-1"></i>{{ __('messages.deadlines') }}
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-person me-1"></i>{{ __('messages.profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
