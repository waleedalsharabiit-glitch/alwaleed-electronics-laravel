<aside class="sidebar">
    <div class="sidebar-brand">
        🛒 متجر الوليد
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('user.dashboard') }}" class="active">حسابي الشخصي</a></li>
        <li><a href="#">طلباتي السابقة</a></li>
        <li><a href="#">المفضلة</a></li>
        <li><a href="#">سلة التسوق</a></li>
        <li><a href="#">إعدادات الحساب</a></li>
    </ul>
    <div class="sidebar-user">
        <small style="color: var(--text-muted);">أهلاً بك،</small>
        <p style="font-weight: bold;">{{ Auth::user()->name }}</p>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger" style="width: 100%; padding: 6px;">تسجيل الخروج</button>
        </form>
    </div>
</aside>

