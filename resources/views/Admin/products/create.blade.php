@extends('layouts.app')

@section('title', 'إضافة جهاز جديد')
@section('header_title', 'تعريف جهاز جديد والمواصفات الفنية')

@section('content')

@if($errors->any())
    <div style="background: #fef2f2; color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; max-width: 900px; margin-left: auto; margin-right: auto;">
        <ul style="margin: 0; padding-right: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="dashboard-box" style="max-width: 900px; margin: 0 auto;">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">اسم الجهاز</label>
                <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="مثال: Cisco Router 2901" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">رمز الجهاز (SKU)</label>
                <input type="text" name="sku" class="form-control-custom" value="{{ old('sku') }}" placeholder="مثال: CS-RTR-2901" required>
                @error('sku') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">القسم</label>
                <select name="category_id" class="form-control-custom" required>
                    <option value="">-- اختر القسم --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">الماركة / الشركة</label>
                <select name="brand_id" class="form-control-custom" required>
                    <option value="">-- اختر الماركة --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">وثيقة الضمان</label>
                <select name="warranty_id" class="form-control-custom">
                    <option value="">-- بدون ضمان --</option>
                    @foreach($warranties as $w)
                        <option value="{{ $w->id }}" {{ old('warranty_id') == $w->id ? 'selected' : '' }}>{{ $w->name }} ({{ $w->duration_months }} شهر)</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">السعر الأصلي ($)</label>
                <input type="number" step="0.01" name="price" class="form-control-custom" value="{{ old('price') }}" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">سعر الخصم ($)</label>
                <input type="number" step="0.01" name="sale_price" class="form-control-custom" value="{{ old('sale_price') }}">
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">المخزون المتوفر</label>
                <input type="number" name="stock_quantity" class="form-control-custom" value="{{ old('stock_quantity', 1) }}" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">تنبيه المخزون الحرج</label>
                <input type="number" name="critical_stock_alert" class="form-control-custom" value="{{ old('critical_stock_alert', 2) }}" required>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 6px; font-weight: 600;">صورة الجهاز الرئيسية</label>
            <input type="file" name="image" class="form-control-custom" accept="image/*">
        </div>

        {{-- قسم المواصفات الفنية الديناميكية Dynamic Specs --}}
        <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h4 style="margin: 0; color: var(--primary-dark);">المواصفات الفنية المخصصة (RAM, CPU, Interfaces...)</h4>
                <button type="button" id="add-spec-btn" style="background: #0284c7; color: white; border: none; padding: 4px 10px; border-radius: 6px; cursor: pointer;">+ إضافة خاصية</button>
            </div>
            
            <div id="specs-container">
                @php
                    $oldSpecs = old('specs', [['key' => '', 'value' => '']]);
                @endphp
                @foreach($oldSpecs as $index => $spec)
                    <div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 8px;">
                        <input type="text" name="specs[{{ $index }}][key]" value="{{ $spec['key'] ?? '' }}" placeholder="اسم الخاصية (مثال: RAM)" class="form-control-custom" style="flex: 1;">
                        <input type="text" name="specs[{{ $index }}][value]" value="{{ $spec['value'] ?? '' }}" placeholder="القيمة (مثال: 16GB DDR4)" class="form-control-custom" style="flex: 1;">
                        @if($index > 0)
                            <button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: white; border: none; padding: 0 10px; border-radius: 6px; cursor: pointer;">X</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn-auth">حفظ الجهاز بالكامل</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let specIndex = {{ count(old('specs', [0])) }};
        const addButton = document.getElementById('add-spec-btn');
        const container = document.getElementById('specs-container');

        addButton.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'spec-row';
            row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 8px;';
            row.innerHTML = `
                <input type="text" name="specs[${specIndex}][key]" placeholder="اسم الخاصية" class="form-control-custom" style="flex: 1;">
                <input type="text" name="specs[${specIndex}][value]" placeholder="القيمة" class="form-control-custom" style="flex: 1;">
                <button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: white; border: none; padding: 0 10px; border-radius: 6px; cursor: pointer;">X</button>
            `;
            container.appendChild(row);
            specIndex++;
        });
    });
</script>

@endsection