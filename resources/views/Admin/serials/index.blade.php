@extends('layouts.app')

@section('title', 'إدارة وتتبع الأرقام التسلسلية')
@section('header_title', 'تتبع الأرقام التسلسلية وحالات المخزون (Serial Tracking)')

@section('content')

{{-- رسائل النجاح والأخطاء --}}
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

    {{-- نموذج إضافة سيريالات جديدة (فردي / مجمع) --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">إدخال أرقام تسلسلية جديدة</h3>
        </div>

        <form action="{{ route('admin.serials.store') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">اختر الجهاز المرتبط</label>
                <select name="product_id" class="form-control-custom" required>
                    <option value="">-- اختر الجهاز --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (SKU: {{ $p->sku }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">حالة القطع المضافة</label>
                <select name="status" class="form-control-custom" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>متاحة بالمخزن (Available)</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>تحت الصيانة (Maintenance)</option>
                    <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>تالفة / معطوبة (Damaged)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">سيريال فردي</label>
                <input type="text" name="serial_number" class="form-control-custom" value="{{ old('serial_number') }}" placeholder="مثال: SN-CS-998823">
                @error('serial_number') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">أو إدخال مجمّع (Bulk Imports)</label>
                <textarea name="bulk_serials" class="form-control-custom" rows="4" placeholder="أدخل كل رقم تسلسلي في سطر جديد...">{{ old('bulk_serials') }}</textarea>
                <small style="color: var(--text-muted); font-size: 0.75rem;">ضع كل سيريال في سطر مستقل للإدخال السريع.</small>
            </div>

            <button type="submit" class="btn-auth">تخزين الأرقام التسلسلية</button>
        </form>
    </div>

    {{-- جدول عرض وتتبع السيريالات مع الفلترة --}}
    <div class="dashboard-box">
        <div class="box-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">جدول تتبع السيريالات</h3>

            {{-- شريط الفلترة --}}
            <form method="GET" action="{{ route('admin.serials.index') }}" style="display: flex; gap: 8px;">
                <select name="status" onchange="this.form.submit()" class="form-control-custom" style="padding: 4px 8px; font-size: 0.8rem;">
                    <option value="">جميع الحالات</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>متاح</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>مباع</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                    <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>معطوب</option>
                </select>
            </form>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>اسم الجهاز</th>
                    <th>الحالة التشغيلية</th>
                    <th>تحديث الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serials as $serial)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold; color: var(--primary-dark);">
                            {{ $serial->serial_number }}
                        </td>
                        <td>{{ $serial->product?->name ?? 'غير محدد / محذوف' }}</td>
                        <td>
                            @if($serial->status === 'available')
                                <span class="badge badge-success">متاح بالمخزن</span>
                            @elseif($serial->status === 'sold')
                                <span class="badge badge-info">مباع</span>
                            @elseif($serial->status === 'maintenance')
                                <span class="badge badge-warning">صيانة</span>
                            @else
                                <span class="badge badge-danger">معطوب</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.serials.update-status', $serial->id) }}" method="POST" style="display: flex; gap: 4px;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="padding: 2px 6px; font-size: 0.78rem; border-radius: 4px; border: 1px solid #cbd5e1;">
                                    <option value="available" {{ $serial->status == 'available' ? 'selected' : '' }}>متاح</option>
                                    <option value="sold" {{ $serial->status == 'sold' ? 'selected' : '' }}>مباع</option>
                                    <option value="maintenance" {{ $serial->status == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                                    <option value="damaged" {{ $serial->status == 'damaged' ? 'selected' : '' }}>معطوب</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.serials.destroy', $serial->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('حذف هذا السيريال سيقوم بخصم قطعة من المخزون التلقائي، هل تريد الاستمرار؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted);">لا توجد أرقام تسلسلية مسجلة وفقاً للفلتر.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($serials, 'links'))
            <div style="margin-top: 15px;">
                {{ $serials->links() }}
            </div>
        @endif
    </div>

</div>

@endsection