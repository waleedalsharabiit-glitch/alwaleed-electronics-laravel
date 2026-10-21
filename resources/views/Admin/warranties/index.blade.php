@extends('layouts.app')

@section('title', 'إدارة وثائق وسياسات الضمان')
@section('header_title', 'إدارة وثائق الضمان والمدة الزمنية')

@section('content')

@if(session('success'))
    <div style="background: #e6f9ed; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
        {{ session('success') }}
    </div>
@endif

<div class="two-column-layout">

    {{-- نموذج إضافة وثيقة ضمان جديدة --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">إضافة سياسة ضمان جديدة</h3>
        </div>

        <form action="{{ route('admin.warranties.store') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">اسم الوثيقة / السياسة</label>
                <input type="text" name="title" class="form-control-custom" value="{{ old('title') }}" placeholder="مثال: ضمان العتاد الذهبي" required>
                @error('title') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">مدة الضمان (بالأشهر)</label>
                <input type="number" name="duration_months" class="form-control-custom" value="{{ old('duration_months', 12) }}" min="1" max="120" required>
                @error('duration_months') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">نوع التغطية</label>
                <select name="coverage_type" class="form-control-custom" required>
                    <option value="full" {{ old('coverage_type') == 'full' ? 'selected' : '' }}>شامل (صيانة وقطع غيار)</option>
                    <option value="hardware_only" {{ old('coverage_type') == 'hardware_only' ? 'selected' : '' }}>عيوب التصنيع والعتاد فقط</option>
                    <option value="maintenance_only" {{ old('coverage_type') == 'maintenance_only' ? 'selected' : '' }}>أجور صيانة فقط</option>
                </select>
                @error('coverage_type') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">الشروط والأحكام</label>
                <textarea name="terms" class="form-control-custom" rows="3" placeholder="أدخل الشروط الاستثنائية للضمان...">{{ old('terms') }}</textarea>
                @error('terms') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 0.88rem; font-weight: 600; cursor: pointer;">تفعيل الوثيقة للاستخدام الفوري</label>
            </div>

            <button type="submit" class="btn-auth">حفظ سياسة الضمان</button>
        </form>
    </div>

    {{-- جدول عرض وثائق الضمان --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">وثائق الضمان المعتمدة</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>اسم الوثيقة</th>
                    <th>المدة</th>
                    <th>نوع التغطية</th>
                    <th>الحالة</th>
                    <th>الأجهزة المرتبطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warranties as $warranty)
                    <tr>
                        <td style="font-weight: 600;">{{ $warranty->title }}</td>
                        <td><span class="badge badge-info">{{ $warranty->duration_months }} شهر</span></td>
                        <td>
                            @if($warranty->coverage_type === 'full')
                                <span class="badge badge-success">شامل</span>
                            @elseif($warranty->coverage_type === 'hardware_only')
                                <span class="badge badge-warning">عتاد فقط</span>
                            @else
                                <span class="badge badge-info">صيانة</span>
                            @endif
                        </td>
                        <td>
                            @if($warranty->is_active)
                                <span style="color: #10b981; font-weight: 600;">مفعل</span>
                            @else
                                <span style="color: #ef4444; font-weight: 600;">معطل</span>
                            @endif
                        </td>
                        <td><span class="badge badge-warning">{{ $warranty->products_count }} جهاز</span></td>
                        <td>
                            <form action="{{ route('admin.warranties.destroy', $warranty->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت تأكد من حذف هذه الوثيقة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">لا توجد وثائق ضمان مضافة حتى الآن.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection