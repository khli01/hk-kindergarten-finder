<?php

return [
    // Authentication
    'login' => '登录',
    'logout' => '退出',
    'register' => '注册',
    'email' => '邮箱地址',
    'password' => '密码',
    'remember_me' => '记住我',
    'forgot_password' => '忘记密码？',
    'confirm_password' => '确认密码',
    'name' => '姓名',

    // Messages
    'failed' => '这些凭证与我们的记录不符。',
    'password_incorrect' => '密码不正确。',
    'throttle' => '登录尝试次数过多。请在 :seconds 秒后重试。',
    'login_required' => '请先登录。',
    'unauthorized' => '您没有权限访问此页面。',

    // Registration
    'registration_success' => '注册成功！请查看您的邮箱以验证账户。',
    'already_registered' => '已有账户？',
    'create_account' => '创建账户',

    // Email Verification
    'verify_email' => '验证邮箱',
    'verify_email_subject' => '验证您的邮箱地址',
    'verify_email_first' => '请先验证您的邮箱地址。',
    'verification_link_sent' => '新的验证链接已发送到您的邮箱。',
    'email_verified' => '您的邮箱已验证。您现在可以登录。',
    'already_verified' => '您的邮箱已经验证。',
    'invalid_verification_link' => '无效或已过期的验证链接。',
    'resend_verification' => '重新发送验证邮件',
    'check_email_verification' => '请查看您的邮箱以获取验证链接。',
    'did_not_receive_email' => '没有收到邮件？',

    // Password Reset
    'reset_password' => '重置密码',
    'reset_password_subject' => '重置您的密码',
    'send_reset_link' => '发送重置链接',
    'reset_link_sent' => '如果此邮箱存在账户，您将收到密码重置链接。',
    'password_reset_success' => '您的密码已重置。您现在可以使用新密码登录。',
    'invalid_reset_token' => '无效或已过期的密码重置令牌。',
    'reset_token_expired' => '此密码重置链接已过期。',
    'user_not_found' => '找不到用户。',

    // Success Messages
    'login_success' => '欢迎回来！',
    'logout_success' => '您已退出。',

    // Validation
    'password_requirements' => '密码必须至少8个字符，并包含大写、小写字母和数字。',
];
