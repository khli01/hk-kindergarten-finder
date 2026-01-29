@extends('layouts.app')

@section('title', __('messages.my_suggestions') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold">{{ __('messages.my_suggestions') }}</h1>
                    <p class="text-muted mb-0">{{ $suggestions->total() }} suggestions</p>
                </div>
                <a href="{{ route('suggestions.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i>{{ __('messages.submit_suggestion') }}
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        {{ __('messages.suggestion_info') }}
    </div>

    @if($suggestions->count() > 0)
        <div class="card">
            <div class="list-group list-group-flush">
                @foreach($suggestions as $suggestion)
                    <a href="{{ route('suggestions.show', $suggestion) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-secondary me-2">{{ $suggestion->localized_category }}</span>
                                    @if($suggestion->kindergarten)
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-building me-1"></i>{{ $suggestion->kindergarten->localized_name }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mb-1">{{ Str::limit($suggestion->content, 150) }}</p>
                                <small class="text-muted">
                                    {{ $suggestion->created_at->format('Y-m-d H:i') }} 
                                    ({{ $suggestion->created_at->diffForHumans() }})
                                </small>
                            </div>
                            <div class="ms-3">
                                <span class="badge {{ 
                                    $suggestion->status === 'pending' ? 'bg-warning text-dark' : 
                                    ($suggestion->status === 'reviewed' ? 'bg-info' : 
                                    ($suggestion->status === 'processed' ? 'bg-success' : 'bg-secondary')) 
                                }}">
                                    {{ $suggestion->localized_status }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $suggestions->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-chat-dots display-1 text-muted"></i>
            <h4 class="mt-3">No suggestions yet</h4>
            <p class="text-muted">Help us improve by sharing your feedback about schools or the platform.</p>
            <a href="{{ route('suggestions.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i>{{ __('messages.submit_suggestion') }}
            </a>
        </div>
    @endif
</div>
@endsection
