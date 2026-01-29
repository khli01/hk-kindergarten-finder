@extends('layouts.app')

@section('title', __('messages.app_name') . ' - ' . __('messages.hero_title'))

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">{{ __('messages.hero_title') }}</h1>
                <p class="lead mb-4">{{ __('messages.hero_subtitle') }}</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('kindergartens.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-search me-2"></i>{{ __('messages.start_search') }}
                    </a>
                    <a href="{{ route('deadlines.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-calendar-event me-2"></i>{{ __('messages.deadlines') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="search-box">
                    <form action="{{ route('kindergartens.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label text-dark">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="{{ __('messages.search') }}...">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-dark">{{ __('messages.all_districts') }}</label>
                                <select name="district" class="form-select">
                                    <option value="">{{ __('messages.all_districts') }}</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->localized_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark">{{ __('messages.class_levels') }}</label>
                                <select name="class_type" class="form-select">
                                    <option value="">{{ __('messages.all_class_types') }}</option>
                                    <option value="pn">{{ __('messages.pn_class') }}</option>
                                    <option value="k1">K1</option>
                                    <option value="k2">K2</option>
                                    <option value="k3">K3</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-search me-2"></i>{{ __('messages.search') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-4">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-4">
                    <h2 class="display-5 fw-bold text-primary mb-2">{{ $stats['total_schools'] }}</h2>
                    <p class="mb-0 text-muted">{{ __('messages.total_schools') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card p-4">
                    <h2 class="display-5 fw-bold text-primary mb-2">{{ $stats['total_districts'] }}</h2>
                    <p class="mb-0 text-muted">{{ __('messages.browse_by_district') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h2 class="display-5 fw-bold text-primary mb-2">{{ $stats['upcoming_deadlines'] }}</h2>
                    <p class="mb-0 text-muted">{{ __('messages.upcoming_deadlines') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Schools -->
@if($featuredKindergartens->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">{{ __('messages.featured_schools') }}</h2>
            <a href="{{ route('kindergartens.index') }}" class="btn btn-outline-primary">
                {{ __('messages.view_all') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row">
            @foreach($featuredKindergartens as $school)
                <div class="col-md-6 col-lg-4 mb-4">
                    @include('components.kindergarten-card', ['kindergarten' => $school])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Upcoming Deadlines -->
@if($upcomingDeadlines->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">{{ __('messages.upcoming_deadlines') }}</h2>
            <a href="{{ route('deadlines.index') }}" class="btn btn-outline-primary">
                {{ __('messages.view_all') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row">
            @foreach($upcomingDeadlines as $deadline)
                <div class="col-md-6 col-lg-4 mb-4">
                    @include('components.deadline-card', ['deadline' => $deadline])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


<!-- Newsletter Subscription -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-envelope-heart text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-3">{{ __("messages.newsletter_title") }}</h3>
                        <p class="text-muted mb-4">
                            {{ __("messages.newsletter_subtitle") }}
                        </p>
                        
                        @if(session("success"))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session("success") }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route("subscribe") }}" method="POST" class="row g-3 justify-content-center">
                            @csrf
                            <div class="col-md-8">
                                <input type="email" 
                                       name="email" 
                                       class="form-control form-control-lg" 
                                       placeholder="{{ __("messages.enter_email") }}" 
                                       required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-send me-2"></i>{{ __("messages.subscribe") }}
                                </button>
                            </div>
                        </form>
                        <small class="text-muted mt-3 d-block">
                            {{ __("messages.newsletter_privacy") }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Browse by District -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">{{ __('messages.browse_by_district') }}</h2>
        
        @php
            $regions = [
                'hong_kong_island' => $districts->where('region', 'hong_kong_island'),
                'kowloon' => $districts->where('region', 'kowloon'),
                'new_territories' => $districts->where('region', 'new_territories'),
            ];
        @endphp

        @foreach($regions as $regionKey => $regionDistricts)
            <div class="mb-4">
                <h5 class="text-muted mb-3">{{ __('messages.' . $regionKey) }}</h5>
                <div class="row">
                    @foreach($regionDistricts as $district)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <a href="{{ route('kindergartens.district', $district) }}" class="text-decoration-none">
                                <div class="card district-card p-3 text-center">
                                    <span class="fw-medium">{{ $district->localized_name }}</span>
                                    <small class="text-muted">{{ $district->kindergartens_count ?? 0 }} {{ __('messages.kindergartens') }}</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
