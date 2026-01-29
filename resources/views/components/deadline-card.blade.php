@php
    $daysRemaining = now()->diffInDays($deadline->deadline_date, false);
    $isUrgent = $daysRemaining >= 0 && $daysRemaining <= 7;
@endphp

<div class="card deadline-card {{ $isUrgent ? 'deadline-urgent' : '' }} h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <span class="badge {{ $isUrgent ? 'bg-danger' : 'bg-warning text-dark' }}">
                {{ $deadline->localized_event_type }}
            </span>
            @if($deadline->is_verified)
                <span class="text-success" title="{{ __('kindergarten.verified_info') }}">
                    <i class="bi bi-patch-check-fill"></i>
                </span>
            @endif
        </div>

        <h6 class="card-title mb-2">
            <a href="{{ route('kindergartens.show', $deadline->kindergarten) }}" class="text-decoration-none text-dark">
                {{ $deadline->kindergarten->localized_name }}
            </a>
        </h6>

        <p class="text-muted small mb-2">
            <i class="bi bi-geo-alt me-1"></i>{{ $deadline->kindergarten->district->localized_name ?? '' }}
        </p>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-calendar3 me-1 text-primary"></i>
                <strong>{{ $deadline->deadline_date->format('Y-m-d') }}</strong>
                @if($deadline->deadline_time)
                    <small class="text-muted">{{ $deadline->deadline_time->format('H:i') }}</small>
                @endif
            </div>
            @if($daysRemaining >= 0)
                <span class="badge {{ $isUrgent ? 'bg-danger' : 'bg-secondary' }}">
                    {{ __('kindergarten.days_remaining', ['days' => $daysRemaining]) }}
                </span>
            @else
                <span class="badge bg-secondary">{{ __('kindergarten.deadline_passed') }}</span>
            @endif
        </div>

        @if($deadline->localized_notes)
            <p class="small text-muted mt-2 mb-0">{{ Str::limit($deadline->localized_notes, 100) }}</p>
        @endif
    </div>
</div>
