@extends('layouts.app')

@section('title', __('messages.kindergartens') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">{{ __('messages.kindergartens') }}</h1>
            <p class="text-muted">
                {{ __('kindergarten.schools_found', ['count' => $kindergartens->total()]) }}
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>{{ __('messages.filter') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kindergartens.index') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="{{ __('messages.search') }}...">
                        </div>

                        <!-- District -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.all_districts') }}</label>
                            <select name="district" class="form-select">
                                <option value="">{{ __('messages.all_districts') }}</option>
                                @foreach($districts->groupBy('region') as $region => $regionDistricts)
                                    <optgroup label="{{ __('messages.' . $region) }}">
                                        @foreach($regionDistricts as $district)
                                            <option value="{{ $district->id }}" 
                                                {{ request('district') == $district->id ? 'selected' : '' }}>
                                                {{ $district->localized_name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <!-- Class Type -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.class_levels') }}</label>
                            <select name="class_type" class="form-select">
                                <option value="">{{ __('messages.all_class_types') }}</option>
                                <option value="pn" {{ request('class_type') == 'pn' ? 'selected' : '' }}>
                                    {{ __('kindergarten.class_pn') }}
                                </option>
                                <option value="k1" {{ request('class_type') == 'k1' ? 'selected' : '' }}>
                                    {{ __('kindergarten.class_k1') }}
                                </option>
                                <option value="k2" {{ request('class_type') == 'k2' ? 'selected' : '' }}>
                                    {{ __('kindergarten.class_k2') }}
                                </option>
                                <option value="k3" {{ request('class_type') == 'k3' ? 'selected' : '' }}>
                                    {{ __('kindergarten.class_k3') }}
                                </option>
                            </select>
                        </div>

                        <!-- School Type -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.school_type') }}</label>
                            <select name="school_type" class="form-select">
                                <option value="">All</option>
                                <option value="private" {{ request('school_type') == 'private' ? 'selected' : '' }}>
                                    {{ __('messages.private') }}
                                </option>
                                <option value="non_profit" {{ request('school_type') == 'non_profit' ? 'selected' : '' }}>
                                    {{ __('messages.non_profit') }}
                                </option>
                                <option value="government" {{ request('school_type') == 'government' ? 'selected' : '' }}>
                                    {{ __('messages.government') }}
                                </option>
                            </select>
                        </div>

                        <!-- Minimum Ranking -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.min_ranking') }}</label>
                            <input type="number" name="min_ranking" class="form-control" 
                                   min="0" max="100" value="{{ request('min_ranking') }}">
                        </div>

                        <!-- Minimum Success Rate -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.min_success_rate') }}</label>
                            <input type="number" name="min_success_rate" class="form-control" 
                                   min="0" max="100" step="0.1" value="{{ request('min_success_rate') }}">
                        </div>

                        <!-- Has PN Class -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="has_pn" value="1" class="form-check-input" 
                                   id="hasPn" {{ request('has_pn') ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasPn">{{ __('messages.has_pn_class') }}</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>{{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('kindergartens.index') }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'ranking', 'dir' => 'desc']) }}" 
                       class="btn btn-sm {{ request('sort', 'ranking') == 'ranking' ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ __('messages.sort_by_ranking') }}
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc']) }}" 
                       class="btn btn-sm {{ request('sort') == 'name' ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ __('messages.sort_by_name') }}
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'success_rate', 'dir' => 'desc']) }}" 
                       class="btn btn-sm {{ request('sort') == 'success_rate' ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ __('messages.sort_by_success_rate') }}
                    </a>
                </div>
            </div>

            <!-- Results Grid -->
            @if($kindergartens->count() > 0)
                <div class="row">
                    @foreach($kindergartens as $kindergarten)
                        <div class="col-md-6 mb-4">
                            @include('components.kindergarten-card', ['kindergarten' => $kindergarten])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $kindergartens->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h4 class="mt-3">{{ __('messages.no_results') }}</h4>
                    <p class="text-muted">Try adjusting your search filters</p>
                    <a href="{{ route('kindergartens.index') }}" class="btn btn-primary">
                        {{ __('messages.view_all') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
