<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'الوليد للإلكترونيات')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-layout">
        
        {{-- استدعاء الشريط الجانبي تلقائياً حسب نوع الحساب --}}
        @if(Auth::check())
            @if(Auth::user()->isAdmin())
                @include('layouts.partials.admin-sidebar')
            @elseif(Auth::user()->isEmployee())
                @include('layouts.partials.employee-sidebar')
            @else
                @include('layouts.partials.user-sidebar')
            @endif
        @endif

        <div class="main-content">
            {{-- الشريط العلوي Navbar --}}
            <header class="top-navbar">
                <div>
                    <h3 style="color: var(--primary-dark);">@yield('header_title', 'لوحة التحكم')</h3>
                </div>
                <div>
                    <span>مرحباً، <strong>{{ Auth::user()->name }}</strong></span>
                </div>
            </header>

            {{-- محتوى الصفحة المتغير --}}
            <main class="page-wrapper">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>