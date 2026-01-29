@extends('layouts.app')

@section('title', __('messages.deadlines') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">{{ __('messages.upcoming_deadlines') }}</h1>
            <p class="text-muted">{{ __('messages.registration_deadlines') }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>{{ __('messages.filter') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('deadlines.index') }}" method="GET">
                        <!-- District Filter -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.all_districts') }}</label>
                            <select name="district" class="form-select">
                                <option value="">{{ __('messages.all_districts') }}</option>
                                @foreach($districts->groupBy('region') as $region => $regionDistricts)
                                    <optgroup label="{{ __('messages.' . $region) }}">
                                        @foreach($regionDistricts as $district)
                                            <option value="{{ $district->id }}" {{ request('district') == $district->id ? 'selected' : '' }}>
                                                {{ $district->localized_name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <!-- Event Type Filter -->
                        <div class="mb-3">
                            <label class="form-label">Event Type</label>
                            <select name="event_type" class="form-select">
                                <option value="">All Events</option>
                                <option value="application_start" {{ request('event_type') == 'application_start' ? 'selected' : '' }}>
                                    {{ __('kindergarten.event_application_start') }}
                                </option>
                                <option value="application_deadline" {{ request('event_type') == 'application_deadline' ? 'selected' : '' }}>
                                    {{ __('kindergarten.event_application_deadline') }}
                                </option>
                                <option value="interview" {{ request('event_type') == 'interview' ? 'selected' : '' }}>
                                    {{ __('kindergarten.event_interview') }}
                                </option>
                                <option value="open_day" {{ request('event_type') == 'open_day' ? 'selected' : '' }}>
                                    {{ __('kindergarten.event_open_day') }}
                                </option>
                                <option value="briefing_session" {{ request('event_type') == 'briefing_session' ? 'selected' : '' }}>
                                    {{ __('kindergarten.event_briefing_session') }}
                                </option>
                            </select>
                        </div>

                        <!-- Academic Year Filter -->
                        <div class="mb-3">
                            <label class="form-label">Academic Year</label>
                            <select name="academic_year" class="form-select">
                                <option value="">All Years</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>{{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('deadlines.index') }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deadlines List -->
        <div class="col-lg-9">
            @if($deadlines->count() > 0)
                <div class="row">
                    @foreach($deadlines as $deadline)
                        <div class="col-md-6 mb-4">
                            @include('components.deadline-card', ['deadline' => $deadline])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $deadlines->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="mt-3">{{ __('messages.no_deadlines') }}</h4>
                    <p class="text-muted">There are no upcoming registration deadlines matching your filters.</p>
                    <a href="{{ route('deadlines.index') }}" class="btn btn-primary">
                        {{ __('messages.view_all') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
