@extends('layouts.app')

@section('title', 'إدارة الحجوزات والطلبات المسبقة')
@section('header_title', 'محرّك الطلبات المسبقة والحجوزات (Pre-Orders Engine)')

@section('content')

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

    {{-- نموذج إنشاء طلب حجز مسبق جديد --}}
    <div class="dashboard-box">
        <div class="box-header">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">تسجيل حجز مسبق جديد</h3>
        </div>

        <form action="{{ route('admin.pre-orders.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 12px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">اسم العميل</label>
                <input type="text" name="customer_name" class="form-control-custom" placeholder="مثال: وليد الشرعبي" value="{{ old('customer_name') }}" required>
                @error('customer_name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">رقم هاتف العميل</label>
                <input type="text" name="customer_phone" class="form-control-custom" placeholder="770000000" value="{{ old('customer_phone') }}" required>
                @error('customer_phone') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">الجهاز المطلوب حجزه</label>
                <select name="product_id" class="form-control-custom" required>
                    <option value="">-- اختر الجهاز --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (${{ number_format($p->price, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">الكمية</label>
                    <input type="number" name="quantity" class="form-control-custom" value="{{ old('quantity', 1) }}" min="1" required>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">الدفعة المقدمة ($)</label>
                    <input type="number" step="0.01" name="deposit_amount" class="form-control-custom" value="{{ old('deposit_amount', 0) }}" min="0" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">تاريخ الوصول المتوقع</label>
                    <input type="date" name="expected_date" class="form-control-custom" value="{{ old('expected_date') }}" required>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">حالة الطلب</label>
                    <select name="status" class="form-control-custom" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار (Pending)</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>مؤكد (Confirmed)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.88rem;">ملاحظات استثنائية</label>
                <textarea name="notes" class="form-control-custom" rows="2" placeholder="تأكيد مواصفات لون أو طراز خاص...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn-auth">حفظ طلب الحجز المسبق</button>
        </form>
    </div>

    {{-- جدول عرض وتتبع الطلبات المسبقة --}}
    <div class="dashboard-box">
        <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">قائمة الحجوزات النشطة</h3>

            <form method="GET" action="{{ route('admin.pre-orders.index') }}">
                <select name="status" onchange="this.form.submit()" class="form-control-custom" style="padding: 4px 8px; font-size: 0.8rem;">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="arrived" {{ request('status') == 'arrived' ? 'selected' : '' }}>وصل للمخزن</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </form>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>الكود / العميل</th>
                    <th>الجهاز / الكمية</th>
                    <th>الماليات (مقدم / متبقي)</th>
                    <th>تاريخ الوصول</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preOrders as $po)
                    <tr>
                        <td>
                            <div style="font-family: monospace; font-weight: bold; color: #0284c7;">{{ $po->reference_code }}</div>
                            <div style="font-weight: 600; color: var(--primary-dark);">{{ $po->customer_name }}</div>
                            <small style="color: var(--text-muted);">{{ $po->customer_phone }}</small>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $po->product?->name ?? 'غير محدد' }}</div>
                            <span class="badge badge-info">الكمية: {{ $po->quantity }}</span>
                        </td>
                        <td>
                            <div style="color: #10b981; font-size: 0.85rem;">مقدم: ${{ number_format($po->deposit_amount, 2) }}</div>
                            <div style="color: #ef4444; font-size: 0.85rem; font-weight: 600;">متبقي: ${{ number_format($po->remaining_amount, 2) }}</div>
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #475569;">
                                {{ \Carbon\Carbon::parse($po->expected_date)->format('Y-m-d') }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.pre-orders.update-status', $po->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="padding: 2px 6px; font-size: 0.78rem; border-radius: 4px; border: 1px solid #cbd5e1;">
                                    <option value="pending" {{ $po->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="confirmed" {{ $po->status == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                    <option value="arrived" {{ $po->status == 'arrived' ? 'selected' : '' }}>وصل الشحنة</option>
                                    <option value="delivered" {{ $po->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                    <option value="cancelled" {{ $po->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.pre-orders.destroy', $po->id) }}" method="POST" onsubmit="return confirm('هل أنت تأكد من إلغاء وحذف الحجز؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 600;">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">لا توجد طلبات حجز مسبق مسجلة حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($preOrders, 'links'))
            <div style="margin-top: 15px;">
                {{ $preOrders->links() }}
            </div>
        @endif
    </div>

</div>

@endsection