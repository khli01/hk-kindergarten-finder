<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #4A90A4, #2E6B7D); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e0e0e0; border-top: none; }
        .button { display: inline-block; background-color: #4A90A4; color: white !important; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">{{ __('messages.app_name') }}</h1>
        </div>
        <div class="content">
            <h2>{{ __('auth.verify_email') }}</h2>
            <p>Hello {{ $user->name }},</p>
            <p>Thank you for registering with {{ config('app.name') }}. Please click the button below to verify your email address:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">{{ __('auth.verify_email') }}</a>
            </div>
            
            <p>If you did not create an account, no further action is required.</p>
            
            <p>If you're having trouble clicking the button, copy and paste the following URL into your browser:</p>
            <p style="word-break: break-all; color: #666; font-size: 12px;">{{ $verificationUrl }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
