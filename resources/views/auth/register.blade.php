<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - الوليد للإلكترونيات</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-brand">
                <span>⚡ الوليد للإلكترونيات</span>
            </div>
            <p class="auth-subtitle">أنشئ حسابك للاستفادة من جميع خدمات المتجر والضمان</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">الاسم بالكامل</label>
                <!-- تم جعل الـ placeholder عاماً -->
                <input type="text" id="name" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="أدخل اسمك الكامل" required autofocus>
                @error('name')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="name@example.com" required>
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

            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-auth">إنشاء الحساب</button>
        </form>

        <div class="auth-footer">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a>
        </div>
    </div>
</div>

</body>
</html>