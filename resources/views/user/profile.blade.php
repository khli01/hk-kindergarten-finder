@extends('layouts.app')

@section('title', __('messages.profile') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">{{ __('messages.profile') }}</h1>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('messages.update_profile') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('auth.name') }}</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!$user->hasVerifiedEmail())
                                <small class="text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>{{ __('auth.verify_email_first') }}
                                </small>
                            @else
                                <small class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Verified
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="preferred_language" class="form-label">{{ __('messages.language_preference') }}</label>
                            <select name="preferred_language" id="preferred_language" class="form-select">
                                @foreach(config('app.available_locales') as $locale => $name)
                                    <option value="{{ $locale }}" {{ $user->preferred_language === $locale ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('messages.save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('messages.change_password') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <small class="text-muted">{{ __('auth.password_requirements') }}</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('messages.change_password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Info -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Member Since:</strong> 
                            <span class="text-muted">{{ $user->created_at->format('Y-m-d') }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Email Verified:</strong> 
                            @if($user->hasVerifiedEmail())
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-warning text-dark">No</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
