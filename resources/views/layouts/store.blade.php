<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر الوليد للإلكترونيات')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');
        * { font-family: 'Cairo', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- الشريط العلوي الإعلاني -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <span>⚡ مرحباً بك في متجر الوليد للإلكترونيات - أفضل العروض والضمانات الحقيقية</span>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="tel:+967000000000" class="hover:text-white transition"><i class="fa-solid fa-phone text-blue-400 ml-1"></i> الدعم الفني</a>
            </div>
        </div>
    </div>

    <!-- الهيدر الرئيسي وشريط التنقل -->
    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- اللوجو -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="bg-blue-600 text-white p-2 rounded-xl shadow-md shadow-blue-500/30">
                            <i class="fa-solid fa-bolt text-xl"></i>
                        </span>
                        <span>الوليد <span class="text-blue-600">للإلكترونيات</span></span>
                    </a>
                </div>

                <!-- شريط البحث السريع -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form action="{{ route('store.catalog') }}" method="GET" class="w-full relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="ابحث عن جهاز، ماركة، أو قسم..." 
                               class="w-full bg-slate-100 text-slate-800 text-sm rounded-full py-2.5 pr-4 pl-10 outline-none border border-transparent focus:border-blue-500 focus:bg-white transition">
                        <button type="submit" class="absolute left-3 top-2.5 text-slate-400 hover:text-blue-600">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <!-- أدوات المستخدم والعمليات -->
                <div class="flex items-center space-x-5 space-x-reverse">
                    
                    <!-- السلة -->
                    <a href="{{ route('store.cart') }}" class="relative p-2 text-slate-700 hover:text-blue-600 transition">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>

                    <!-- قائمة الحساب -->
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-800 px-3 py-2 rounded-xl text-sm font-semibold transition">
                                <i class="fa-solid fa-user-circle text-lg text-blue-600"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 hidden group-hover:block z-50">
                                @if(in_array(Auth::user()->role, ['admin', 'employee']))
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-bold text-blue-600 hover:bg-slate-50">
                                        <i class="fa-solid fa-chart-line ml-2"></i> لوحة الإدارة
                                    </a>
                                @endif
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fa-solid fa-box ml-2"></i> طلباتي وضماناتي
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fa-solid fa-right-from-bracket ml-2"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md shadow-blue-500/20 transition">
                            تسجيل الدخول
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- شريط التصفح السفلي -->
        <nav class="bg-slate-900 text-white text-sm border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 flex items-center space-x-6 space-x-reverse overflow-x-auto py-3">
                <a href="{{ route('store.customer.dashboard') }}" class="hover:text-blue-400 transition whitespace-nowrap"><i class="fa-solid fa-house ml-1"></i> الرئيسية</a>
                <a href="{{ route('store.catalog') }}" class="hover:text-blue-400 transition whitespace-nowrap"><i class="fa-solid fa-store ml-1"></i> كافة المنتجات</a>
                <a href="{{ route('store.bundles') }}" class="text-amber-400 font-bold hover:text-amber-300 transition whitespace-nowrap"><i class="fa-solid fa-gift ml-1"></i> حزم العروض الخاصة ⚡</a>
            </div>
        </nav>
    </header>

    <!-- المحتوى المتغير -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between">
                    <span><i class="fa-solid fa-circle-check ml-2"></i> {{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between">
                    <span><i class="fa-solid fa-circle-exclamation ml-2"></i> {{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- التذييل (Footer) -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-white text-lg font-bold mb-4">⚡ الوليد للإلكترونيات</h3>
                <p class="text-slate-400 leading-relaxed text-xs">
                    متجرك الأول للأجهزة الإلكترونية المضمونة. نوفر لك أفضل الأجهزة الذكية، الحاسبات، والملحقات مع خدمة تتبع الأرقام التسلسلية والضمانات الموثوقة.
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">روابط سريعة</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('store.catalog') }}" class="hover:text-white transition">تصفح الكتالوج</a></li>
                    <li><a href="{{ route('store.bundles') }}" class="hover:text-white transition">عروض الحزم</a></li>
                    <li><a href="{{ route('customer.dashboard') }}" class="hover:text-white transition">متابعة الضمان والطلبات</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">تواصل معنا</h4>
                <p class="text-xs text-slate-400 mb-2"><i class="fa-solid fa-location-dot ml-2"></i> اليمن - تعز</p>
                <p class="text-xs text-slate-400 mb-2"><i class="fa-solid fa-envelope ml-2"></i> info@alwaleed-electronics.com</p>
            </div>
        </div>
        <div class="border-t border-slate-800 text-center py-4 text-xs text-slate-500">
            جميع الحقوق محفوظة © {{ date('Y') }} - شركة الوليد للإلكترونيات
        </div>
    </footer>

    @stack('scripts')
</body>
</html>