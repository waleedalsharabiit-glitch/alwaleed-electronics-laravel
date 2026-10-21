@extends('layouts.app')

@section('title', 'واجهة المتجر الكتالوج والسلة')
@section('header_title', 'نظام نقطة البيع والمتجر الإلكتروني (Storefront & POS)')

@section('content')

@if(session('error'))
    <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
        {{ session('error') }}
    </div>
@endif

<div class="two-column-layout" style="grid-template-columns: 2fr 1fr;">

    {{-- كروت المنتجات --}}
    <div>
        <h3 style="margin-bottom: 15px; color: var(--primary-dark);">المنتجات المتاحة بالمخزن</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px;">
            @foreach($products as $p)
                <div class="dashboard-box" style="padding: 15px; text-align: center; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <div style="font-weight: 700; font-size: 1rem; color: var(--primary-dark); margin-bottom: 5px;">{{ $p->name }}</div>
                    <div style="color: #64748b; font-size: 0.8rem; margin-bottom: 10px;">SKU: {{ $p->sku }}</div>
                    <div style="font-size: 1.2rem; font-weight: 800; color: #10b981; margin-bottom: 10px;">${{ number_format($p->price, 2) }}</div>
                    <span class="badge badge-info" style="margin-bottom: 12px; display: inline-block;">متوفر: {{ $p->stock_quantity }}</span>
                    
                    <button type="button" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->price }}, {{ $p->stock_quantity }})" 
                            class="btn-auth" style="padding: 8px; font-size: 0.85rem; background: #3b82f6;">
                        + إضافة للسلة
                    </button>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 15px;">{{ $products->links() }}</div>
    </div>

    {{-- جانب سلة الشراء وإتمام الطلب --}}
    <div class="dashboard-box" style="height: fit-content; position: sticky; top: 20px;">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">سلة الشراء (Cart)</h3>
        </div>

        <!-- تم تعديل اسم المسار إلى checkout.process -->
        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            
            <div style="margin-bottom: 12px;">
                <input type="text" name="customer_name" class="form-control-custom" placeholder="اسم العميل" required>
            </div>
            <div style="margin-bottom: 15px;">
                <input type="text" name="customer_phone" class="form-control-custom" placeholder="رقم الهاتف" required>
            </div>

            <div id="cart-items" style="max-height: 250px; overflow-y: auto; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                <p id="empty-cart-msg" style="text-align: center; color: #94a3b8; font-size: 0.85rem;">السلة فارغة حالياً.</p>
            </div>

            <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 1.1rem; margin-bottom: 15px; color: var(--primary-dark);">
                <span>الإجمالي:</span>
                <span id="cart-total">$0.00</span>
            </div>

            <button type="submit" class="btn-auth" style="background: #10b981;">إتمام الشراء وتوليد الفاتورة 🧾</button>
        </form>
    </div>

</div>

<script>
    let cart = [];

    function addToCart(id, name, price, maxStock) {
        let existing = cart.find(item => item.product_id === id);
        if (existing) {
            if (existing.quantity < maxStock) {
                existing.quantity++;
            } else {
                alert('عذراً، وصلت للحد الأقصى للمخزون المتوفر!');
            }
        } else {
            cart.push({ product_id: id, name: name, price: price, quantity: 1, maxStock: maxStock });
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const totalEl = document.getElementById('cart-total');

        if (cart.length === 0) {
            container.innerHTML = '<p id="empty-cart-msg" style="text-align: center; color: #94a3b8; font-size: 0.85rem;">السلة فارغة حالياً.</p>';
            totalEl.innerText = '$0.00';
            return;
        }

        let html = '';
        let total = 0;

        cart.forEach((item, index) => {
            let itemTotal = item.price * item.quantity;
            total += itemTotal;
            /* تعديل اسم الحقول المخفية من cart إلى items لتتوافق مع الـ Controller */
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; background: #f8fafc; padding: 6px 10px; border-radius: 6px;">
                    <div>
                        <strong style="font-size: 0.85rem; display: block;">${item.name}</strong>
                        <small style="color: #64748b;">$${item.price} × ${item.quantity}</small>
                    </div>
                    <div>
                        <button type="button" onclick="removeFromCart(${index})" style="background:none; border:none; color:#ef4444; cursor:pointer; font-weight:bold;">×</button>
                    </div>
                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                </div>
            `;
        });

        container.innerHTML = html;
        totalEl.innerText = '$' + total.toFixed(2);
    }
</script>

@endsection