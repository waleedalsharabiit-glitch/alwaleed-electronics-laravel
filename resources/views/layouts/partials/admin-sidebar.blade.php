<aside class="sidebar">
    <div class="sidebar-brand">
        ⚡ الوليد للإلكترونيات
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 لوحة التحكم
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                📦 إدارة المنتجات
            </a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                📂 الأقسام
            </a>
        </li>
        <li>
            <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                🏷️ الماركات
            </a>
        </li>
        <li>
            <a href="{{ route('admin.warranties.index') }}" class="{{ request()->routeIs('admin.warranties.*') ? 'active' : '' }}">
                🛡️ وثائق الضمان
            </a>
        </li>
        <li>
            <a href="{{ route('admin.serials.index') }}" class="{{ request()->routeIs('admin.serials.*') ? 'active' : '' }}">
                🔢 الأرقام التسلسلية
            </a>
        </li>
        <li>
            <a href="{{ route('admin.bundles.index') }}" class="{{ request()->routeIs('admin.bundles.*') ? 'active' : '' }}">
                🎁 حزم العروض
            </a>
        </li>
        <li>
            <a href="{{ route('admin.pre-orders.index') }}" class="{{ request()->routeIs('admin.pre-orders.*') ? 'active' : '' }}">
                ⏳ الطلبات المسبقة والحجوزات
            </a>
        </li>
        <li>
            <a href="{{ route('store.home') }}" target="_blank">
                🛒 المتجر ونقطة البيع (POS) ↗
            </a>
        </li>
    </ul>

    <div class="sidebar-user">
        <small style="color: var(--text-muted);">المسؤول الحالي:</small>
        <p style="font-weight: bold;">{{ Auth::user()->name }}</p>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger" style="width: 100%; padding: 6px;">تسجيل الخروج</button>
        </form>
    </div>
</aside>