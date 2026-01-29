@extends('layouts.app')

@section('title', __('auth.verify_email') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-white text-center py-4">
                    <h4 class="mb-0">{{ __('auth.verify_email') }}</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-check display-1 text-primary"></i>
                    </div>
                    <p class="mb-4">{{ __('auth.check_email_verification') }}</p>
                    
                    <p class="text-muted mb-4">{{ __('auth.did_not_receive_email') }}</p>
                    
                    <form action="{{ route('verification.resend') }}" method="POST">
                        @csrf
                        <input type="email" name="email" class="form-control mb-3" 
                               placeholder="{{ __('auth.email') }}" 
                               value="{{ auth()->user()->email ?? old('email') }}" required>
                        <button type="submit" class="btn btn-primary">
                            {{ __('auth.resend_verification') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
