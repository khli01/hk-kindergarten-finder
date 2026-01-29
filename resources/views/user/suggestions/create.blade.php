@extends('layouts.app')

@section('title', __('messages.submit_suggestion') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ __('messages.submit_suggestion') }}</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-shield-lock me-2"></i>
                        {{ __('messages.suggestion_info') }}
                    </div>

                    <form action="{{ route('suggestions.store') }}" method="POST">
                        @csrf

                        <!-- Related Kindergarten (Optional) -->
                        <div class="mb-3">
                            <label for="kindergarten_id" class="form-label">
                                Related Kindergarten (Optional)
                            </label>
                            <select name="kindergarten_id" id="kindergarten_id" class="form-select">
                                <option value="">-- General Feedback --</option>
                                @foreach($kindergartens as $k)
                                    <option value="{{ $k->id }}" {{ (old('kindergarten_id') ?? optional($kindergarten)->id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->localized_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kindergarten_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('messages.suggestion_category') }} *</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                <option value="school_info" {{ old('category') == 'school_info' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_school_info') }}
                                </option>
                                <option value="ranking_feedback" {{ old('category') == 'ranking_feedback' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_ranking_feedback') }}
                                </option>
                                <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_feature_request') }}
                                </option>
                                <option value="data_correction" {{ old('category') == 'data_correction' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_data_correction') }}
                                </option>
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_general') }}
                                </option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>
                                    {{ __('kindergarten.category_other') }}
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label">{{ __('messages.suggestion_content') }} *</label>
                            <textarea name="content" id="content" rows="6" 
                                      class="form-control @error('content') is-invalid @enderror" 
                                      placeholder="{{ __('messages.suggestion_placeholder') }}" 
                                      required minlength="10" maxlength="5000">{{ old('content') }}</textarea>
                            <div class="form-text">Minimum 10 characters, maximum 5000 characters.</div>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>{{ __('messages.submit') }}
                            </button>
                            <a href="{{ route('suggestions.index') }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
