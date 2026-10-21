@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية')
@section('header_title', 'نظرة عامة على المنظومة والعمليات (Executive Dashboard)')

@section('content')

{{-- كروت الإحصائيات السريعة --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px;">
    
    <div class="dashboard-box" style="padding: 18px; border-right: 4px solid #3b82f6;">
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">إجمالي المنتجات</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary-dark); margin-top: 5px;">
            {{ number_format($stats['total_products'] ?? 0) }}
        </div>
    </div>

    <div class="dashboard-box" style="padding: 18px; border-right: 4px solid #10b981;">
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">السيريالات المتاحة بالمخزن</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #10b981; margin-top: 5px;">
            {{ number_format($stats['available_serials'] ?? 0) }}
        </div>
    </div>

    <div class="dashboard-box" style="padding: 18px; border-right: 4px solid #8b5cf6;">
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">إجمالي المبيعات الإجمالية</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #8b5cf6; margin-top: 5px;">
            ${{ number_format($stats['total_sales'] ?? 0, 2) }}
        </div>
    </div>

    <div class="dashboard-box" style="padding: 18px; border-right: 4px solid #f59e0b;">
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600;">الحجوزات النشطة</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #f59e0b; margin-top: 5px;">
            {{ number_format($stats['active_preorders'] ?? 0) }}
        </div>
    </div>

</div>

{{-- الجداول السريعة للمتابعة --}}
<div class="two-column-layout">

    {{-- آخر الفواتير والمبيعات --}}
    <div class="dashboard-box">
        <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">أحدث المبيعات والفواتير</h3>
            <a href="{{ route('dashboard') }}" style="font-size: 0.8rem; color: #3b82f6; text-decoration: none; font-weight: bold;">تصفح المتجر ↗</a>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>العميل</th>
                    <th>المبلغ</th>
                    <th>عرض</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold; color: #0284c7;">{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td style="font-weight: bold; color: #10b981;">${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('user.orders.show', $order->id) }}" target="_blank" style="color: #475569; text-decoration: none; font-weight: 600; font-size: 0.8rem;">👁️ الفاتورة</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">لا توجد مبيعات مسجلة مؤخراً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- أحدث طلبات الحجز المسبق --}}
    <div class="dashboard-box">
        <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.05rem; color: var(--primary-dark);">أحدث الطلبات المسبقة</h3>
            <a href="{{ route('admin.pre-orders.index') }}" style="font-size: 0.8rem; color: #3b82f6; text-decoration: none; font-weight: bold;">إدارة الحجوزات ↗</a>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>الجهاز</th>
                    <th>تاريخ الوصول</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentPreOrders as $po)
                    <tr>
                        <td style="font-weight: 600;">{{ $po->customer_name }}</td>
                        <td>{{ $po->product->name ?? 'منتج غير محدد' }}</td>
                        <td style="font-size: 0.8rem;">{{ \Carbon\Carbon::parse($po->expected_date)->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge badge-warning">{{ $po->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">لا توجد طلبات حجز مسبق.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection