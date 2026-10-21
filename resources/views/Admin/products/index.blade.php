@extends('layouts.app')

@section('title', 'إدارة الأجهزة والمنتجات')
@section('header_title', 'قائمة الأجهزة والمواصفات الفنية')

@section('content')

@if(session('success'))
    <div style="background: #e6f9ed; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fef2f2; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        {{ session('error') }}
    </div>
@endif

<div class="dashboard-box">
    <div class="box-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 1.05rem; color: var(--primary-dark);">الأجهزة والمنتجات المسجلة</h3>
        <a href="{{ route('admin.products.create') }}" class="btn-auth" style="text-decoration: none; padding: 8px 16px; font-size: 0.88rem;">+ إضافة جهاز جديد</a>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>اسم الجهاز / SKU</th>
                <th>القسم / الماركة</th>
                <th>السعر</th>
                <th>المخزون / التنبيه</th>
                <th>الضمان</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 45px; height: 45px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: bold;">💻</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--primary-dark);">{{ $product->name }}</div>
                        <small style="color: var(--text-muted);">SKU: {{ $product->sku }}</small>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $product->category?->name ?? 'بدون قسم' }}</span>
                        <span class="badge badge-success">{{ $product->brand?->name ?? 'بدون ماركة' }}</span>
                    </td>
                    <td>
                        @if($product->sale_price)
                            <span style="text-decoration: line-through; color: #888; font-size: 0.8rem;">${{ number_format($product->price, 2) }}</span>
                            <span style="color: #10b981; font-weight: 600;">${{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span style="font-weight: 600;">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($product->stock_quantity <= $product->critical_stock_alert)
                            <span class="badge badge-danger" title="المخزون وصل للحد الحرج!">
                                {{ $product->stock_quantity }} قطع (حرج)
                            </span>
                        @else
                            <span class="badge badge-info">{{ $product->stock_quantity }} قطع</span>
                        @endif
                    </td>
                    <td>
                        @if($product->warranty)
                            <span style="font-size: 0.85rem;">{{ $product->warranty->name }} ({{ $product->warranty->duration_months }}m)</span>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.85rem;">بدون ضمان</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" style="color: #0284c7; text-decoration: none; font-weight: 600; margin-left: 10px;">تعديل</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من حذف هذا الجهاز؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted);">لا توجد أجهزة مضافة حتى الآن.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(method_exists($products, 'links'))
        <div style="margin-top: 15px;">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection