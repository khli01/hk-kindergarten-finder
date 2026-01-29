@extends('layouts.app')

@section('title', __('messages.my_suggestions') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Link -->
            <a href="{{ route('suggestions.index') }}" class="btn btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') }}
            </a>

            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Suggestion Details</h5>
                        <span class="badge {{ 
                            $suggestion->status === 'pending' ? 'bg-warning text-dark' : 
                            ($suggestion->status === 'reviewed' ? 'bg-info' : 
                            ($suggestion->status === 'processed' ? 'bg-success' : 'bg-secondary')) 
                        }}">
                            {{ $suggestion->localized_status }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-muted small">Category</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ $suggestion->localized_category }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Submitted</label>
                            <p class="mb-0">{{ $suggestion->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    @if($suggestion->kindergarten)
                        <div class="mb-4">
                            <label class="text-muted small">Related Kindergarten</label>
                            <p class="mb-0">
                                <a href="{{ route('kindergartens.show', $suggestion->kindergarten) }}" class="text-decoration-none">
                                    <i class="bi bi-building me-1"></i>{{ $suggestion->kindergarten->localized_name }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="text-muted small">Your Feedback</label>
                        <div class="p-3 bg-light rounded">
                            {{ $suggestion->content }}
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Your suggestion is being reviewed. Thank you for helping us improve!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
