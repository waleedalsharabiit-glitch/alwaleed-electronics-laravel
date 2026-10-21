<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الشفاء الرقمي - صيدليتي</title>
    
    <!-- أيقونات FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --sky-light: #eff6ff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-main: #f8fafc;
            --sidebar-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /* العودة إلى خط النظام الأساسي البسيط */
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            direction: rtl;
            text-align: right;
            min-height: 100vh;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar أبيض وسماوي أنيق */
        .main-sidebar {
            width: 280px;
            min-width: 280px;
            background: var(--sidebar-bg);
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 28px 20px;
            flex-shrink: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            z-index: 10;
        }

        /* الهيدر العلوي */
        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 22px;
            border-bottom: 2px dashed #f1f5f9;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            transition: transform 0.3s ease;
        }

        .brand-header:hover .brand-icon {
            transform: scale(1.05) rotate(-5deg);
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        /* القائمة الجانبية وتأثيرات الـ Hover */
        .sidebar-nav {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-grow: 1;
        }

        .nav-item {
            position: relative;
        }

        .nav-item a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 12px;
            transition: all 0.25s ease;
            overflow: hidden;
        }

        /* خط إرشادي جانبي عند الـ Hover */
        .nav-item a::before {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 4px;
            background: var(--primary-blue);
            border-radius: 4px 0 0 4px;
            opacity: 0;
            transform: scaleY(0);
            transition: all 0.25s ease;
        }

        .nav-item a:hover {
            background-color: var(--sky-light);
            color: var(--primary-blue);
            transform: translateX(-5px);
            padding-right: 20px;
        }

        .nav-item a:hover::before {
            opacity: 1;
            transform: scaleY(1);
        }

        .nav-item a:hover i {
            transform: scale(1.15);
            color: var(--primary-blue);
        }

        /* العنصر المفعّل Active */
        .nav-item.active a {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            font-weight: 700;
            transform: none !important;
            padding-right: 16px;
        }

        .nav-item.active a::before {
            display: none;
        }

        .nav-item.active a i {
            color: #ffffff !important;
        }

        .nav-item i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            transition: transform 0.25s ease, color 0.25s ease;
        }

        /* كرت المستخدم في الأسفل */
        .user-profile-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #f8fafc;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            background: #dbeafe;
            color: var(--primary-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .logout-btn {
            background: #fef2f2;
            border: none;
            color: #ef4444;
            font-size: 1.05rem;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: #ffffff;
            transform: scale(1.05);
        }

        .page-content {
            flex: 1;
            padding: 35px;
            overflow-y: auto;
            background-color: var(--bg-main);
            width: calc(100% - 280px);
        }
    </style>
</head>
<body>

<div class="app-layout">
    
    <aside class="main-sidebar">
        <div>
            <div class="brand-header">
                <div class="brand-icon">
                    <i class="fas fa-prescription-bottle-alt"></i>
                </div>
                <span class="brand-title">الشفاء الرقمي</span>
            </div>

            <ul class="sidebar-nav">
                {{-- إذا كان المستخدم أدمن --}}
                @if(auth()->check() && (strtolower(auth()->user()->role ?? '') === 'admin' || auth()->user()->is_admin == 1))
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}">
                            <i class="fas fa-chart-pie"></i>
                            <span>لوحة التحكم العامة</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('users.index') ? route('users.index') : '#' }}">
                            <i class="fas fa-users"></i>
                            <span>إدارة كل المستخدمين</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('categories.index') ? route('categories.index') : '#' }}">
                            <i class="fas fa-tags"></i>
                            <span>إدارة الأقسام والتصنيفات</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('medicines.index') || request()->routeIs('medicines.create') ? 'active' : '' }}">
                        <a href="{{ Route::has('medicines.index') ? route('medicines.index') : '#' }}">
                            <i class="fas fa-pills"></i>
                            <span>إدارة الأدوية والمخزن</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('medicines.issued') || request()->routeIs('issued.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('medicines.issued') ? route('medicines.issued') : '#' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>سجلات الأدوية المصروفة</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('requests.*') || request()->routeIs('medicines.requests') ? 'active' : '' }}">
                        <a href="{{ Route::has('requests.index') ? route('requests.index') : (Route::has('medicines.requests') ? route('medicines.requests') : '#') }}">
                            <i class="fas fa-bell"></i>
                            <span>طلبات الأدوية الواردة</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('reports.index') ? route('reports.index') : '#' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>التقارير والتصدير</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('settings.index') ? route('settings.index') : '#' }}">
                            <i class="fas fa-cog"></i>
                            <span>إعدادات النظام</span>
                        </a>
                    </li>

                {{-- إذا كان مستخدم عادي --}}
                @else
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}">
                            <i class="fas fa-home"></i>
                            <span>الرئيسية</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.medicines.*') ? 'active' : '' }}">
                        <a href="{{ Route::has('user.medicines.view') ? route('user.medicines.view') : '#' }}">
                            <i class="fas fa-pills"></i>
                            <span>استعراض الأدوية</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.issue.request*') ? 'active' : '' }}">
                        <a href="{{ Route::has('user.issue.request') ? route('user.issue.request') : '#' }}">
                            <i class="fas fa-file-medical"></i>
                            <span>طلب صرف دواء</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.medicine.provide*') ? 'active' : '' }}">
                        <a href="{{ Route::has('user.medicine.provide') ? route('user.medicine.provide') : '#' }}">
                            <i class="fas fa-hand-holding-medical"></i>
                            <span>طلب توفير دواء</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <a href="{{ Route::has('user.profile') ? route('user.profile') : '#' }}">
                            <i class="fas fa-user"></i>
                            <span>ملفي الشخصي</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="user-profile-footer">
            <div class="user-details">
                <div class="avatar-circle">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name ?? 'مستخدم' }}</span>
                    <span class="user-role">
                        {{ (auth()->check() && (strtolower(auth()->user()->role ?? '') === 'admin' || auth()->user()->is_admin == 1)) ? 'مدير النظام' : 'مستفيد نشط' }}
                    </span>
                </div>
            </div>

            <!-- زر تسجيل الخروج -->
            <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn" title="تسجيل الخروج" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="page-content">
        @yield('content')
    </main>

</div>

</body>
</html>