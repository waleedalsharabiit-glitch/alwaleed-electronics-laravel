@extends('layouts.store')

@section('title', 'كتالوج المنتجات - متجر الوليد')

@section('content')
<style>
    .catalog-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .catalog-header {
        margin-bottom: 30px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
    }

    .catalog-title {
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 8px 0;
    }

    .catalog-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .catalog-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }

    @media (min-width: 992px) {
        .catalog-layout {
            grid-template-columns: 260px 1fr;
        }
    }

    /* الشريط الجانبي للفلاتر */
    .filter-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .filter-section-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group {
        margin-bottom: 20px;
    }

    .filter-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
    }

    .filter-select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 13px;
        color: #0f172a;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .filter-select:focus {
        border-color: #2563eb;
        background-color: #ffffff;
    }

    .btn-filter {
        width: 100%;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 13px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn-filter:hover {
        background-color: #1d4ed8;
    }

    /* شبكة المنتجات */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .product-card-item {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08);
    }

    .product-image-box {
        background-color: #0f172a;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #334155;
        font-size: 50px;
        position: relative;
    }

    .product-info {
        padding: 15px;
    }

    .product-badge {
        font-size: 10px;
        font-weight: 800;
        background-color: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .product-item-title {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px 0;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-brand {
        font-size: 11px;
        color: #94a3b8;
        margin: 0 0 12px 0;
    }

    .product-footer {
        padding: 12px 15px;
        background-color: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .product-price {
        font-size: 16px;
        font-weight: 900;
        color: #2563eb;
    }

    .btn-details {
        background-color: #0f172a;
        color: #ffffff;
        font-size: 11px;
        font-weight: bold;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .btn-details:hover {
        background-color: #2563eb;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        color: #64748b;
    }

    .pagination-wrapper {
        margin-top: 30px;
    }
</style>

<div class="catalog-container">
    <div class="catalog-header">
        <h1 class="catalog-title">كتالوج المنتجات</h1>
        <p class="catalog-subtitle">استعرض أحدث الأجهزة والتجهيزات التقنية المتاحة بضمان فعال</p>
    </div>

    <div class="catalog-layout">
        <!-- تصفية الفلاتر Sidebar -->
        <aside class="filter-card">
            <h3 class="filter-section-title">
                <i class="fa-solid fa-filter"></i> تصفية المنتجات
            </h3>
            <form action="{{ route('store.catalog') }}" method="GET">
                <div class="filter-group">
                    <label class="filter-label" for="category">القسم</label>
                    <select name="category_id" id="category" class="filter-select">
                        <option value="">جميع الأقسام</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="brand">العلامة التجارية</label>
                    <select name="brand_id" id="brand" class="filter-select">
                        <option value="">جميع الماركات</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    تطبيق الفلترة
                </button>
            </form>
        </aside>

        <!-- قائمة عرض المنتجات Grid -->
        <main>
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card-item">
                            <div>
                                <div class="product-image-box">
                                    <i class="fa-solid fa-microchip"></i>
                                </div>
                                <div class="product-info">
                                    <span class="product-badge">{{ $product->category->name ?? 'عام' }}</span>
                                    <h3 class="product-item-title" title="{{ $product->name }}">{{ $product->name }}</h3>
                                    <p class="product-brand">الماركة: {{ $product->brand->name ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="product-footer">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('store.product.show', $product->id) }}" class="btn-details">
                                    التفاصيل
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- الترقيم (Pagination) -->
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-box-open" style="font-size: 40px; margin-bottom: 15px; color: #cbd5e1;"></i>
                    <p>لا توجد منتجات مطابقة لخيارات البحث حالياً.</p>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection