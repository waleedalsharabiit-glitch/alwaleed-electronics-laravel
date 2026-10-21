@extends('layouts.store')

@section('title', $product->name)

@section('content')
<style>
    .product-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .product-card {
        background-color: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        padding: 30px;
        display: grid;
        grid-template-columns: 1fr;
        gap: 40px;
    }

    @media (min-width: 768px) {
        .product-card {
            grid-template-columns: 1fr 1fr;
        }
    }

    .image-preview-box {
        background-color: #0f172a;
        border-radius: 16px;
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .image-preview-icon {
        font-size: 100px;
        color: #1e293b;
    }

    .badge-stock {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: bold;
    }

    .warranty-box {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        padding: 18px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
    }

    .warranty-icon {
        width: 45px;
        height: 45px;
        background-color: rgba(37, 99, 235, 0.1);
        color: #2563eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        flex-shrink: 0;
    }

    .warranty-title {
        font-size: 13px;
        font-weight: bold;
        color: #0f172a;
        margin: 0;
    }

    .warranty-desc {
        font-size: 12px;
        color: #64748b;
        margin-top: 3px;
    }

    .category-tags {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .badge-category {
        font-size: 12px;
        font-weight: bold;
        background-color: #eff6ff;
        color: #2563eb;
        padding: 4px 12px;
        border-radius: 8px;
    }

    .brand-text {
        font-size: 12px;
        font-weight: bold;
        color: #94a3b8;
    }

    .product-title {
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.3;
        margin: 0 0 15px 0;
    }

    .price-box {
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        padding: 15px 0;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }

    .price-amount {
        font-size: 32px;
        font-weight: 900;
        color: #2563eb;
    }

    .price-subtext {
        font-size: 12px;
        color: #94a3b8;
    }

    .product-description {
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
        margin: 20px 0;
    }

    .cart-form {
        margin-top: 20px;
    }

    .cart-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .quantity-control {
        width: 130px;
        background-color: #f1f5f9;
        padding: 8px 12px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quantity-label {
        font-size: 12px;
        font-weight: bold;
        color: #64748b;
    }

    .quantity-input {
        width: 45px;
        background: transparent;
        text-align: center;
        font-weight: 900;
        color: #0f172a;
        border: none;
        outline: none;
        font-size: 14px;
    }

    .btn-add-cart {
        flex: 1;
        background-color: #2563eb;
        color: #ffffff;
        font-weight: bold;
        padding: 14px 24px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .btn-add-cart:hover {
        background-color: #1d4ed8;
        transform: translateY(-2px);
    }
</style>

<div class="product-container">
    <div class="product-card">
        
        <!-- معرض الصور والضمان -->
        <div>
            <div class="image-preview-box">
                <i class="fa-solid fa-microchip image-preview-icon"></i>
                <span class="badge-stock">
                    <i class="fa-solid fa-check-circle"></i> متوفر في المخزن ({{ $product->stock_quantity }})
                </span>
            </div>
            
            <div class="warranty-box">
                <div class="warranty-icon">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <h4 class="warranty-title">تأمين الضمان السريالي الفعال</h4>
                    <p class="warranty-desc">يأتي الجهاز مصحوباً بضمان {{ $product->warranty->name ?? 'قياسي' }} لمدة {{ $product->warranty->duration_months ?? 12 }} شهراً مسجل بالسيريال نمبر في حسابك.</p>
                </div>
            </div>
        </div>

        <!-- معلومات المنتج والشرية -->
        <div>
            <div>
                <div class="category-tags">
                    <span class="badge-category">{{ $product->category->name ?? 'قسم عام' }}</span>
                    <span class="brand-text">/ {{ $product->brand->name ?? 'علامة تجارية' }}</span>
                </div>
                <h1 class="product-title">{{ $product->name }}</h1>
            </div>

            <div class="price-box">
                <span class="price-amount">${{ number_format($product->price, 2) }}</span>
                <span class="price-subtext">شامل الضريبة وتأمين الضمان</span>
            </div>

            <p class="product-description">
                {{ $product->description ?? 'لا يوجد وصف تفصيلي إضافي متاح حالياً للمنتج.' }}
            </p>

            <!-- نموذج إضافة للسلة -->
            <form action="{{ route('store.cart.add') }}" method="POST" class="cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="cart-actions">
                    <div class="quantity-control">
                        <span class="quantity-label">الكمية:</span>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="quantity-input">
                    </div>
                    <button type="submit" class="btn-add-cart">
                        <i class="fa-solid fa-cart-shopping"></i> إضافة إلى السلة
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection