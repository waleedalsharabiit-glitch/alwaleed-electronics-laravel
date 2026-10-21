<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - الوليد للإلكترونيات</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-brand">
                <span>⚡ الوليد للإلكترونيات</span>
            </div>
            <p class="auth-subtitle">مرحباً بك مجدداً، يُرجى تسجيل الدخول للوصول للحساب</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-auth">تسجيل الدخول</button>
        </form>

        <div class="auth-footer">
            ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ حساباً الآن</a>
        </div>
    </div>
</div>

</body>
</html>