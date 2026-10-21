@extends('layouts.store')

@section('title', 'لوحة تحكم العميل - طلباتي وضماناتي')

@section('content')
<div class="dashboard-container">
    
    <!-- هيدر اللوحة -->
    <div class="dashboard-header">
        <div class="user-info">
            <span class="user-badge">حساب العميل</span>
            <h1 class="welcome-title">مرحباً بك، {{ auth()->user()->name }}</h1>
            <p class="user-phone">
                <i class="fa-solid fa-phone phone-icon"></i> {{ auth()->user()->phone ?? 'رقم الهاتف غير مسجل' }}
            </p>
        </div>
        <div class="orders-summary-card">
            <span class="summary-label">إجمالي الطلبات</span>
            <span class="summary-count">{{ $orders->total() }}</span>
        </div>
    </div>

    <!-- جدول الطلبات والسيريالات -->
    <div class="orders-card">
        <h3 class="orders-card-title">
            <i class="fa-solid fa-boxes-stacked title-icon"></i> سجل الطلبات والأجهزة المسجلة
        </h3>

        @if($orders->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-receipt empty-icon"></i>
                <p class="empty-text">لم تقم بإجراء أي طلبات حتى الآن.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>تاريخ الطلب</th>
                            <th>إجمالي الفاتورة</th>
                            <th>حالة الدفع</th>
                            <th>الضمان والسيريالات</th>
                            <th class="text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="table-row">
                                <td class="invoice-number">{{ $order->order_number ?? '#INV-'.$order->id }}</td>
                                <td class="order-date">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="order-total">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                                        {{ $order->payment_status == 'paid' ? 'مدفوع' : 'معلق' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="serials-badge">
                                        <i class="fa-solid fa-barcode badge-icon"></i> {{ $order->orderItems->count() }} قطع برقم سريال
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-action">
                                        عرض الفاتورة <i class="fa-solid fa-arrow-left btn-icon"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection