<aside class="sidebar">
    <div class="sidebar-brand">
        🛠️ بوابة الموظف
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('admin.dashboard') }}" class="active">الرئيسية</a></li>
        <li><a href="#">إدخال منتجات جديدة</a></li>
        <li><a href="#">متابعة المخزون</a></li>
        <li><a href="#">فحص الضمان والـ Serials</a></li>
    </ul>
    <div class="sidebar-user">
        <small style="color: var(--text-muted);">الموظف:</small>
        <p style="font-weight: bold;">{{ Auth::user()->name }}</p>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger" style="width: 100%; padding: 6px;">تسجيل الخروج</button>
        </form>
    </div>
</aside>