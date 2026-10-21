@extends('layouts.app')

@section('title', 'إدارة الماركات والشركات المصنعة')
@section('header_title', 'إدارة الشركات المصنعة والشعارات')

@section('content')

@if(session('success'))
    <div style="background: #e6f9ed; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

<div class="two-column-layout">

    {{-- نموذج إضافة ماركة جديدة --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">إضافة ماركة جديدة</h3>
        </div>

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">اسم الماركة التجارية</label>
                <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="مثال: Cisco أو Dell" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">شعار الماركة (Logo)</label>
                <input type="file" name="logo" class="form-control-custom">
                @error('logo') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-auth">حفظ الماركة</button>
        </form>
    </div>

    {{-- جدول عرض الماركات --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">الماركات المسجلة في النظام</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>الشعار</th>
                    <th>اسم الماركة</th>
                    <th>عدد المنتجات المرتبطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="width: 36px; height: 36px; object-fit: contain; border-radius: 6px;">
                            @else
                                <span style="font-size: 1.2rem;">🏢</span>
                            @endif
                        </td>
                        <td style="font-weight: 600;">{{ $brand->name }}</td>
                        <td><span class="badge badge-info">{{ $brand->products_count }} منتج</span></td>
                        <td>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من عملية الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">لا توجد ماركات مضافة حتى الآن.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection