@extends('layouts.store')

@section('title', 'الرئيسية - متجر الوليد للإلكترونيات')

@section('content')
<style>
    /* Global Container */
    .store-container {
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    /* Hero Section */
    .hero-wrapper {
        position: relative;
        overflow: hidden;
        background-color: #020617;
        padding-top: 5rem;
        padding-bottom: 5rem;
        border-bottom: 1px solid #1e293b;
    }

    .hero-bg-radial {
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at top, rgba(30, 58, 138, 0.3), #020617, #020617);
    }

    .hero-grid {
        position: relative;
        z-index: 10;
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
        align-items: center;
    }

    @media (min-width: 768px) {
        .hero-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .hero-content {
        text-align: right;
    }

    .badge-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: rgba(59, 130, 246, 0.1);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.2);
        font-size: 0.75rem;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-weight: 600;
        backdrop-filter: blur(12px);
    }

    .ping-dot {
        position: relative;
        display: inline-flex;
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        background-color: #3b82f6;
    }

    .ping-dot::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background-color: #3b82f6;
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .hero-title {
        font-size: 2.25rem;
        line-height: 1.2;
        font-weight: 900;
        color: #ffffff;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        .hero-title {
            font-size: 3.75rem;
        }
    }

    .hero-gradient-text {
        background: linear-gradient(to right, #60a5fa, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-desc {
        color: #94a3b8;
        font-size: 0.875rem;
        line-height: 1.625;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        .hero-desc {
            font-size: 1rem;
        }
    }

    .hero-buttons {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-top: 0.5rem;
    }

    .btn-primary-hero {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 700;
        padding: 0.875rem 1.75rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-primary-hero:hover {
        background-color: #3b82f6;
        transform: translateY(-2px);
    }

    .btn-secondary-hero {
        background-color: #0f172a;
        border: 1px solid #1e293b;
        color: #cbd5e1;
        font-weight: 700;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-secondary-hero:hover {
        border-color: #334155;
    }

    /* Bundle Hero Card */
    .bundle-card {
        background-color: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid #1e293b;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
    }

    .badge-amber {
        background-color: #f59e0b;
        color: #020617;
        font-weight: 900;
        font-size: 10px;
        padding: 0.25rem 0.625rem;
        border-radius: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        display: inline-block;
    }

    .bundle-card-title {
        color: #ffffff;
        font-size: 1.125rem;
        font-weight: 700;
        margin: 0;
    }

    .bundle-card-desc {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    .bundle-card-footer {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(30, 41, 59, 0.8);
        padding-top: 1rem;
    }

    .bundle-price {
        font-size: 1.5rem;
        font-weight: 900;
        color: #34d399;
    }

    .btn-details-link {
        font-size: 0.75rem;
        background-color: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 700;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-details-link:hover {
        background-color: #2563eb;
        color: #ffffff;
    }

    /* Section Headers */
    .section-wrapper {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
    }

    .section-subtitle {
        color: #2563eb;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        display: block;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
    }

    /* Categories Section */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    @media (min-width: 768px) {
        .categories-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .category-card {
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        padding: 1.25rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .category-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #dbeafe;
    }

    .category-icon-wrapper {
        width: 3.5rem;
        height: 3.5rem;
        margin-left: auto;
        margin-right: auto;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-icon-wrapper {
        background-color: #2563eb;
        color: #ffffff;
        transform: scale(1.1);
    }

    .category-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.875rem;
        margin: 0;
        transition: color 0.3s ease;
    }

    .category-card:hover .category-name {
        color: #2563eb;
    }

    /* Products Section */
    .products-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .product-card {
        background-color: #ffffff;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .product-card-body {
        padding: 1.25rem;
    }

    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .badge-category-tag {
        font-size: 10px;
        font-weight: 800;
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.25rem 0.625rem;
        border-radius: 0.375rem;
    }

    .badge-guaranteed {
        font-size: 10px;
        font-weight: 700;
        color: #059669;
        background-color: #ecfdf5;
        padding: 0.125rem 0.5rem;
        border-radius: 0.25rem;
    }

    .product-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.875rem;
        margin: 0 0 0.25rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-name {
        color: #2563eb;
    }

    .product-brand {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
    }

    .product-card-footer {
        padding: 1.25rem;
        background-color: rgba(248, 250, 252, 0.5);
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-label {
        font-size: 10px;
        color: #94a3b8;
        display: block;
        font-weight: 700;
    }

    .product-price-val {
        font-size: 1.125rem;
        font-weight: 900;
        color: #0f172a;
    }

    .btn-product-details {
        background-color: #0f172a;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        text-decoration: none;
    }

    .btn-product-details:hover {
        background-color: #2563eb;
    }

    .catalog-link {
        font-size: 0.75rem;
        font-weight: 700;
        color: #2563eb;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .catalog-link:hover {
        color: #1e40af;
    }
</style>

<!-- Hero Section الاحترافي -->
<div class="hero-wrapper">
    <div class="hero-bg-radial"></div>
    <div class="store-container">
        <div class="hero-grid">
            <div class="hero-content">
                <span class="badge-hero">
                    <span class="ping-dot"></span> أحدث التقنيات بضمان حقيقي
                </span>
                <h1 class="hero-title">
                    عالم الإلكترونيات <br><span class="hero-gradient-text">بين يديك مباشرة</span>
                </h1>
                <p class="hero-desc">
                    متجر الوليد نوفر لك أحدث الحاسبات، الهواتف، والملحقات الأصلية 100% مع نظام التتبع بالرقم التسلسلي وضمان حقيقي فعال.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('store.catalog') }}" class="btn-primary-hero">
                        تسوق الآن <i class="fa-solid fa-arrow-left" style="margin-right: 0.5rem;"></i>
                    </a>
                    <a href="{{ route('store.bundles') }}" class="btn-secondary-hero">
                        العروض الخاصة ⚡
                    </a>
                </div>
            </div>

            <!-- البطاقات التفاعلية للحزم -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="bundle-card">
                    <span class="badge-amber">حزمة متميزة</span>
                    <h3 class="bundle-card-title">باقة الأداء الفائق للمحترفين</h3>
                    <p class="bundle-card-desc">تتضمن جهاز حاسوب محمول + حقيبة جلدية + ماوس لاسلكي احترافي</p>
                    <div class="bundle-card-footer">
                        <span class="bundle-price">$1,299.00</span>
                        <a href="{{ route('store.catalog') }}" class="btn-details-link">التفاصيل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الأقسام الرئيسية -->
<div class="store-container section-wrapper">
    <div class="section-header">
        <div>
            <span class="section-subtitle">تصفح حسب الفئة</span>
            <h2 class="section-title">أقسام المنتجات</h2>
        </div>
    </div>
    <div class="categories-grid">
        @foreach($categories as $category)
            <a href="{{ route('store.catalog', ['category_id' => $category->id]) }}" class="category-card">
                <div class="category-icon-wrapper">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <h4 class="category-name">{{ $category->name }}</h4>
            </a>
        @endforeach
    </div>
</div>

<!-- أحدث المنتجات الشبكية -->
<div class="store-container" style="padding-bottom: 5rem;">
    <div class="section-header">
        <div>
            <span class="section-subtitle">أحدث الوصولات</span>
            <h2 class="section-title">منتجات مختارة لك</h2>
        </div>
        <a href="{{ route('store.catalog') }}" class="catalog-link">عرض الكتالوج كامل ←</a>
    </div>

    <div class="products-grid">
        @foreach($latestProducts as $product)
            <div class="product-card">
                <div class="product-card-body">
                    <div class="product-meta">
                        <span class="badge-category-tag">{{ $product->category->name ?? 'عام' }}</span>
                        <span class="badge-guaranteed"><i class="fa-solid fa-shield-halved" style="margin-left: 0.25rem;"></i> مضمونه</span>
                    </div>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-brand">الماركة: {{ $product->brand->name ?? 'غير محدد' }}</p>
                </div>
                <div class="product-card-footer">
                    <div>
                        <span class="price-label">السعر</span>
                        <span class="product-price-val">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <a href="{{ route('store.product.show', $product->id) }}" class="btn-product-details">
                        التفاصيل
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection