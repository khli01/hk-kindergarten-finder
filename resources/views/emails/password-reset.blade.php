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
        .warning { background-color: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 4px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">{{ __('messages.app_name') }}</h1>
        </div>
        <div class="content">
            <h2>{{ __('auth.reset_password') }}</h2>
            <p>Hello {{ $user->name }},</p>
            <p>We received a request to reset your password. Click the button below to create a new password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">{{ __('auth.reset_password') }}</a>
            </div>
            
            <div class="warning">
                <strong>Note:</strong> This link will expire in 60 minutes.
            </div>
            
            <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
            
            <p>If you're having trouble clicking the button, copy and paste the following URL into your browser:</p>
            <p style="word-break: break-all; color: #666; font-size: 12px;">{{ $resetUrl }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
