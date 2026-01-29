<div class="card card-school h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="card-title mb-1">
                    <a href="{{ route('kindergartens.show', $kindergarten) }}" class="text-decoration-none text-dark">
                        {{ $kindergarten->localized_name }}
                    </a>
                </h5>
                <small class="text-muted">
                    <i class="bi bi-geo-alt me-1"></i>{{ $kindergarten->district->localized_name }}
                </small>
            </div>
            @if($kindergarten->ranking_score > 0)
                <span class="ranking-badge">
                    <i class="bi bi-star-fill me-1"></i>{{ $kindergarten->ranking_score }}
                </span>
            @endif
        </div>

        @if($kindergarten->primary_success_rate)
            <div class="mb-3">
                <small class="text-muted">{{ __('messages.primary_success_rate') }}</small>
                <div class="success-rate">
                    <i class="bi bi-graph-up-arrow me-1"></i>{{ number_format($kindergarten->primary_success_rate, 1) }}%
                </div>
            </div>
        @endif

        <div class="mb-3">
            @foreach($kindergarten->available_classes as $class)
                <span class="class-badge">{{ $class }}</span>
            @endforeach
        </div>

        @if($kindergarten->features->count() > 0)
            <div class="mb-3">
                @foreach($kindergarten->features->take(3) as $feature)
                    <span class="feature-tag">{{ $feature->localized_value }}</span>
                @endforeach
            </div>
        @endif

        @if($kindergarten->fee_range)
            <div class="mb-3">
                <small class="text-muted">
                    <i class="bi bi-currency-dollar me-1"></i>{{ $kindergarten->fee_range }}/{{ __('messages.monthly_fee') }}
                </small>
            </div>
        @endif
    </div>
    <div class="card-footer bg-transparent border-0">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('kindergartens.show', $kindergarten) }}" class="btn btn-outline-primary btn-sm">
                {{ __('messages.view') }} <i class="bi bi-arrow-right"></i>
            </a>
            @if($kindergarten->website_url)
                <a href="{{ $kindergarten->website_url }}" target="_blank" class="btn btn-link btn-sm text-muted">
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>
