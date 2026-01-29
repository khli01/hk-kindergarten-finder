@extends('layouts.app')

@section('title', $kindergarten->localized_name . ' - ' . __('messages.app_name'))

@section('meta_description', Str::limit($kindergarten->localized_description ?? __('messages.hero_subtitle'), 160))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kindergartens.index') }}">{{ __('messages.kindergartens') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kindergartens.district', $kindergarten->district) }}">{{ $kindergarten->district->localized_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $kindergarten->localized_name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- School Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 fw-bold mb-2">{{ $kindergarten->localized_name }}</h1>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt me-1"></i>{{ $kindergarten->district->localized_name }}
                                @if($kindergarten->school_type)
                                    <span class="mx-2">|</span>
                                    <span class="badge bg-secondary">{{ __('messages.' . $kindergarten->school_type) }}</span>
                                @endif
                            </p>
                        </div>
                        @if($kindergarten->ranking_score > 0)
                            <div class="text-center">
                                <div class="ranking-badge fs-4 px-4 py-2">
                                    <i class="bi bi-star-fill me-1"></i>{{ $kindergarten->ranking_score }}
                                </div>
                                <small class="text-muted d-block mt-1">{{ __('messages.ranking_score') }}</small>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        @if($kindergarten->website_url)
                            <a href="{{ $kindergarten->website_url }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('messages.visit_website') }}
                            </a>
                        @endif
                        
                        @auth
                            <form action="{{ $isFavorited ? route('favorites.destroy', $kindergarten) : route('favorites.store', $kindergarten) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @if($isFavorited)
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }}">
                                    <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }} me-1"></i>
                                    {{ $isFavorited ? __('messages.remove_from_favorites') : __('messages.add_to_favorites') }}
                                </button>
                            </form>
                            
                            <a href="{{ route('suggestions.create', ['kindergarten' => $kindergarten->id]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-chat-dots me-1"></i>{{ __('messages.submit_suggestion') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger">
                                <i class="bi bi-heart me-1"></i>{{ __('messages.add_to_favorites') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Key Stats -->
            <div class="row mb-4">
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center p-3">
                        <div class="display-6 text-primary">
                            @foreach($kindergarten->available_classes as $class)
                                <span class="class-badge">{{ $class }}</span>
                            @endforeach
                        </div>
                        <small class="text-muted">{{ __('messages.class_levels') }}</small>
                    </div>
                </div>

                @if($kindergarten->established_year)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 text-center p-3">
                            <div class="display-6 text-primary">{{ $kindergarten->established_year }}</div>
                            <small class="text-muted">{{ __('messages.established') }}</small>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($kindergarten->localized_description)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">{{ __('messages.school_details') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $kindergarten->localized_description }}</p>
                    </div>
                </div>
            @endif

            <!-- Features -->
            @if($kindergarten->features->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">{{ __('messages.school_features') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($kindergarten->features->groupBy('feature_type') as $type => $features)
                            <div class="mb-3">
                                <h6 class="text-muted">{{ $features->first()->localized_type_name }}</h6>
                                <div>
                                    @foreach($features as $feature)
                                        <span class="feature-tag">{{ $feature->localized_value }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Registration Deadlines -->
            @if($kindergarten->deadlines->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('messages.registration_deadlines') }}</h5>
                        <a href="{{ route('deadlines.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('kindergarten.event_type') ?? 'Event' }}</th>
                                        <th>{{ __('messages.deadlines') ?? 'Date' }}</th>
                                        <th>{{ __('kindergarten.status') ?? 'Status' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kindergarten->deadlines as $deadline)
                                        @php
                                            $daysRemaining = now()->diffInDays($deadline->deadline_date, false);
                                            $isUrgent = $daysRemaining >= 0 && $daysRemaining <= 7;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge {{ $isUrgent ? 'bg-danger' : 'bg-info' }}">
                                                    {{ $deadline->localized_event_type }}
                                                </span>
                                            </td>
                                            <td>{{ $deadline->deadline_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if($daysRemaining >= 0)
                                                    <span class="text-{{ $isUrgent ? 'danger' : 'success' }}">
                                                        {{ __('kindergarten.days_remaining', ['days' => $daysRemaining]) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ __('kindergarten.deadline_passed') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>{{ __('messages.contact') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <strong><i class="bi bi-geo-alt me-2 text-primary"></i>{{ __('messages.address') }}</strong>
                            <p class="mb-0 text-muted">{{ $kindergarten->localized_address }}</p>
                        </li>
                        @if($kindergarten->phone)
                            <li class="mb-3">
                                <strong><i class="bi bi-telephone me-2 text-primary"></i>{{ __('messages.phone') }}</strong>
                                <p class="mb-0"><a href="tel:{{ $kindergarten->phone }}" class="text-decoration-none">{{ $kindergarten->phone }}</a></p>
                            </li>
                        @endif
                        @if($kindergarten->email)
                            <li class="mb-3">
                                <strong><i class="bi bi-envelope me-2 text-primary"></i>{{ __('messages.email') }}</strong>
                                <p class="mb-0"><a href="mailto:{{ $kindergarten->email }}" class="text-decoration-none">{{ $kindergarten->email }}</a></p>
                            </li>
                        @endif
                        @if($kindergarten->website_url)
                            <li class="mb-3">
                                <strong><i class="bi bi-globe me-2 text-primary"></i>{{ __('messages.website') }}</strong>
                                <p class="mb-0"><a href="{{ $kindergarten->website_url }}" target="_blank" class="text-decoration-none">{{ $kindergarten->website_url }}</a></p>
                            </li>
                        @endif
                        @if($kindergarten->principal_name)
                            <li class="mb-3">
                                <strong><i class="bi bi-person me-2 text-primary"></i>{{ __('messages.principal') }}</strong>
                                <p class="mb-0 text-muted">{{ $kindergarten->principal_name }}</p>
                            </li>
                        @endif
                        @if($kindergarten->fee_range)
                            <li>
                                <strong><i class="bi bi-currency-dollar me-2 text-primary"></i>{{ __('messages.monthly_fee') }}</strong>
                                <p class="mb-0 text-muted">{{ $kindergarten->fee_range }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Related Schools -->
            @if($relatedSchools->count() > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">{{ __('messages.featured_schools') }}</h5>
                        <small class="text-muted">{{ $kindergarten->district->localized_name }}</small>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($relatedSchools as $related)
                            <a href="{{ route('kindergartens.show', $related) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $related->localized_name }}</h6>
                                        <small class="text-muted">
                                            @foreach($related->available_classes as $class)
                                                <span class="class-badge">{{ $class }}</span>
                                            @endforeach
                                        </small>
                                    </div>
                                    @if($related->ranking_score > 0)
                                        <span class="badge bg-warning text-dark">{{ $related->ranking_score }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
