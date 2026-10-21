@extends('layouts.app')

@section('title', 'إدارة التصنيفات والأقسام')
@section('header_title', 'إدارة التصنيفات والروابط الفرعية')

@section('content')

{{-- تنبيهات النجاح والأخطاء --}}
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
    
    {{-- نموذج إضافة قسم جديد --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">إضافة قسم جديد</h3>
        </div>
        
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">اسم القسم</label>
                <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="مثال: أجهزة الشبكات" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">القسم الأب (اختياري)</label>
                <select name="parent_id" class="form-control-custom">
                    <option value="">-- قسم رئيسي --</option>
                    @foreach($parentCategories as $pCat)
                        <option value="{{ $pCat->id }}" {{ old('parent_id') == $pCat->id ? 'selected' : '' }}>
                            📁 {{ $pCat->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-auth">حفظ القسم</button>
        </form>
    </div>

    {{-- جدول عرض الأقسام --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">قائمة التصنيفات المعتمدة</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>اسم القسم</th>
                    <th>النوع / القسم الأب</th>
                    <th>عدد المنتجات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="font-weight: 600;">
                            @if($category->parent_id)
                                <span style="color: #94a3b8; margin-left: 5px;">↳</span>
                            @endif
                            {{ $category->name }}
                        </td>
                        <td>
                            @if($category->parent)
                                <span class="badge badge-info">فرعي من: {{ $category->parent->name }}</span>
                            @else
                                <span class="badge badge-success">قسم رئيسي</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-warning">{{ $category->products_count ?? 0 }} منتج</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من عملية الحذف؟ سيتم إلغاء العملية إذا كان القسم يحتوي على عناصر مرتبطة.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">لا توجد تصنيفات مضافة حتى الآن.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($categories, 'links'))
            <div style="margin-top: 15px;">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>

@endsection