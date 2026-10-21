@extends('layouts.app')

@section('title', 'إدارة حزم العروض المجمعة')
@section('header_title', 'محرك حزم العروض المجمعة (Bundles Engine)')

@section('content')

{{-- تنبيهات النجاح والأخطاء --}}
@if(session('success'))
    <div style="background: #e6f9ed; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fef2f2; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        <ul style="margin: 0; padding-right: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="two-column-layout">

    {{-- نموذج إنشاء حزمة عرض جديدة --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">إنشاء حزمة عرض جديدة (Bundle)</h3>
        </div>

        <form action="{{ route('admin.bundles.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">عنوان الحزمة / العرض</label>
                <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="مثال: حزمة الطالب المثالية (لابتوب + حقيبة + ماوس)" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">سعر العرض الخاص (Total Bundle Price)</label>
                <input type="number" step="0.01" name="price" class="form-control-custom" value="{{ old('price') }}" placeholder="0.00" required>
                @error('price') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">وصف العرض</label>
                <textarea name="description" class="form-control-custom" rows="2" placeholder="وصف ميزات العرض والوفر المالي...">{{ old('description') }}</textarea>
                @error('description') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            {{-- اختيار المنتجات التفاعلي --}}
            <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <label style="font-weight: bold; font-size: 0.88rem; color: var(--primary-dark);">عناصر الحزمة (المنتجات)</label>
                    <button type="button" id="add-product-btn" style="background: #e0e7ff; color: #4338ca; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: bold; cursor: pointer;">+ إضافة عنصر</button>
                </div>

                <div id="bundle-products-container">
                    @php
                        $oldProducts = old('products', [
                            ['product_id' => '', 'quantity' => 1],
                            ['product_id' => '', 'quantity' => 1]
                        ]);
                    @endphp

                    @foreach($oldProducts as $index => $oldItem)
                        <div class="bundle-row" style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                            <select name="products[{{ $index }}][product_id]" class="form-control-custom" style="flex: 2;" required>
                                <option value="">-- اختر الجهاز/الملحق --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ $oldItem['product_id'] == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} (${{ number_format($p->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="products[{{ $index }}][quantity]" value="{{ $oldItem['quantity'] ?? 1 }}" min="1" class="form-control-custom" style="flex: 1;" placeholder="الكمية" required>
                            
                            @if($index >= 2)
                                <button type="button" onclick="this.parentElement.remove()" style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-weight: bold;">✕</button>
                            @endif
                        </div>
                    @endforeach
                </div>
                @error('products') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer;">
                    {{-- حقل مخفي يضمن إرسال القيمة 0 عند عدم التحديد --}}
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    تفعيل العرض فوراً في المتجر
                </label>
            </div>

            <button type="submit" class="btn-auth">حفظ ونشر حزمة العرض</button>
        </form>
    </div>

    {{-- جدول عرض وتتبع حزم العروض --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">جدول حزم العروض المجمعة</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>اسم العرض</th>
                    <th>محتويات الحزمة</th>
                    <th>السعر الأصلي</th>
                    <th>سعر العرض</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bundles as $bundle)
                    @php
                        $originalPrice = $bundle->products->sum(fn($item) => $item->price * $item->pivot->quantity);
                        $savings = $originalPrice - $bundle->price;
                    @endphp
                    <tr>
                        <td style="font-weight: bold; color: var(--primary-dark);">
                            {{ $bundle->name }}
                        </td>
                        <td>
                            <ul style="list-style-type: square; padding-right: 15px; margin: 0; font-size: 0.82rem;">
                                @foreach($bundle->products as $item)
                                    <li>{{ $item->name }} <span style="color: var(--text-muted);">(×{{ $item->pivot->quantity }})</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="text-decoration: line-through; color: #94a3b8;">
                            ${{ number_format($originalPrice, 2) }}
                        </td>
                        <td style="font-weight: bold; color: #10b981;">
                            ${{ number_format($bundle->price, 2) }}
                            @if($savings > 0)
                                <div style="font-size: 0.7rem; color: #059669;">وفر ${{ number_format($savings, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($bundle->is_active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-danger">موقوف</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <form action="{{ route('admin.bundles.toggle-status', $bundle->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 0.8rem;">
                                        {{ $bundle->is_active ? 'إيقاف' : 'تفعيل' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.bundles.destroy', $bundle->id) }}" method="POST" onsubmit="return confirm('هل تريد إزالة هذه الحزمة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem;">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">لا توجد حزم عروض مجمعة مضافة حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $bundles->links() }}
        </div>
    </div>

</div>

{{-- قالب صف المنتجات الديناميكي --}}
<template id="bundle-row-template">
    <div class="bundle-row" style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
        <select name="products[INDEX][product_id]" class="form-control-custom" style="flex: 2;" required>
            <option value="">-- اختر الجهاز/الملحق --</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (${{ number_format($p->price, 2) }})</option>
            @endforeach
        </select>
        <input type="number" name="products[INDEX][quantity]" value="1" min="1" class="form-control-custom" style="flex: 1;" placeholder="الكمية" required>
        <button type="button" onclick="this.parentElement.remove()" style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-weight: bold;">✕</button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let productIndex = {{ count(old('products', [0, 1])) }};
        const addButton = document.getElementById('add-product-btn');
        const container = document.getElementById('bundle-products-container');
        const template = document.getElementById('bundle-row-template').innerHTML;

        addButton.addEventListener('click', () => {
            const newRowHtml = template.replace(/INDEX/g, productIndex);
            container.insertAdjacentHTML('beforeend', newRowHtml);
            productIndex++;
        });
    });
</script>

@endsection