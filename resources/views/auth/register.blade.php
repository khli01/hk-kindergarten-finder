@extends('layouts.app')

@section('title', __('auth.register') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-white text-center py-4">
                    <h4 class="mb-0">{{ __('auth.create_account') }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('auth.name') }}</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('auth.password') }}</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <small class="text-muted">{{ __('auth.password_requirements') }}</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="preferred_language" class="form-label">{{ __('messages.language_preference') }}</label>
                            <select name="preferred_language" id="preferred_language" class="form-select">
                                @foreach(config('app.available_locales') as $locale => $name)
                                    <option value="{{ $locale }}" {{ app()->getLocale() === $locale ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('auth.register') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="text-muted">{{ __('auth.already_registered') }}</span>
                    <a href="{{ route('login') }}" class="text-decoration-none ms-1">
                        {{ __('auth.login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
