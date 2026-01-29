@extends('layouts.app')

@section('title', __('messages.contact') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="fw-bold mb-4">{{ __('messages.contact') }}</h1>
            
            <div class="card">
                <div class="card-body">
                    @if(app()->getLocale() === 'zh-TW')
                        <p>如有任何查詢或建議，請透過以下方式聯絡我們：</p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <strong>電郵：</strong> info@hk-kindergarten.com
                            </li>
                        </ul>
                        <p class="text-muted">我們會盡快回覆您的查詢。如您是已註冊用戶，也可以透過網站的「提交意見」功能與我們聯繫。</p>
                    @elseif(app()->getLocale() === 'zh-CN')
                        <p>如有任何查询或建议，请通过以下方式联系我们：</p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <strong>邮箱：</strong> info@hk-kindergarten.com
                            </li>
                        </ul>
                        <p class="text-muted">我们会尽快回复您的查询。如您是已注册用户，也可以通过网站的「提交意见」功能与我们联系。</p>
                    @else
                        <p>For any inquiries or suggestions, please contact us through:</p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <strong>Email:</strong> info@hk-kindergarten.com
                            </li>
                        </ul>
                        <p class="text-muted">We will respond to your inquiry as soon as possible. If you are a registered user, you can also use the "Submit Feedback" feature on our website to contact us.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
