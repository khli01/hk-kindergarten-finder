@extends('layouts.app')

@section('title', __('messages.favorites') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold"><i class="bi bi-heart-fill text-danger me-2"></i>{{ __('messages.favorites') }}</h1>
            <p class="text-muted">{{ $favorites->total() }} {{ __('messages.kindergartens') }}</p>
        </div>
    </div>

    @if($favorites->count() > 0)
        <div class="row">
            @foreach($favorites as $favorite)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('kindergartens.show', $favorite->kindergarten) }}" class="text-decoration-none text-dark">
                                            {{ $favorite->kindergarten->localized_name }}
                                        </a>
                                    </h5>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $favorite->kindergarten->district->localized_name }}
                                    </small>
                                </div>
                                @if($favorite->kindergarten->ranking_score > 0)
                                    <span class="ranking-badge">{{ $favorite->kindergarten->ranking_score }}</span>
                                @endif
                            </div>


                            <div class="mb-3">
                                @foreach($favorite->kindergarten->available_classes as $class)
                                    <span class="class-badge">{{ $class }}</span>
                                @endforeach
                            </div>

                            @if($favorite->notes)
                                <div class="mb-3 p-2 bg-light rounded">
                                    <small class="text-muted"><i class="bi bi-sticky me-1"></i>{{ $favorite->notes }}</small>
                                </div>
                            @endif

                            <!-- Notes Form -->
                            <form action="{{ route('favorites.notes', $favorite->kindergarten) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-sm">
                                    <input type="text" name="notes" class="form-control" 
                                           placeholder="Add notes..." value="{{ $favorite->notes }}">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kindergartens.show', $favorite->kindergarten) }}" class="btn btn-sm btn-outline-primary">
                                    {{ __('messages.view') }}
                                </a>
                                <form action="{{ route('favorites.destroy', $favorite->kindergarten) }}" method="POST" 
                                      onsubmit="return confirm('Remove from favorites?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-heart-fill"></i> {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted"></i>
            <h4 class="mt-3">{{ __('messages.no_results') }}</h4>
            <p class="text-muted">You haven't added any favorites yet. Start browsing kindergartens!</p>
            <a href="{{ route('kindergartens.index') }}" class="btn btn-primary">
                <i class="bi bi-search me-1"></i>{{ __('messages.start_search') }}
            </a>
        </div>
    @endif
</div>
@endsection
