<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة مبيعات #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f1f5f9; margin: 0; padding: 40px; }
        .invoice-card { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px; text-align: right; border-bottom: 1px solid #e2e8f0; }
        .table th { background: #f8fafc; color: #475569; }
        .serial-tag { font-family: monospace; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; }
        .btn-print { background: #0f172a; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; }
        @media print { .no-print { display: none; } body { padding: 0; background: white; } .invoice-card { box-shadow: none; } }
    </style>
</head>
<body>

<div class="invoice-card">
    <div class="no-print" style="margin-bottom: 20px; text-align: left;">
        <button onclick="window.print()" class="btn-print">طباعة الفاتورة 🖨️</button>
    </div>

    <div class="invoice-header">
        <div>
            <h1 style="margin: 0; color: #0f172a; font-size: 1.5rem;">ALWALEED ELECTRONICS</h1>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.9rem;">فاتورة مبيعات معتمدة</p>
        </div>
        <div style="text-align: left;">
            <h3 style="margin: 0; color: #3b82f6;">{{ $order->order_number ?? '#INV-'.$order->id }}</h3>
            <small style="color: #94a3b8;">{{ $order->created_at->format('Y-m-d H:i') }}</small>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <strong style="color: #475569;">بيانات العميل:</strong>
            <div style="font-size: 1.1rem; font-weight: bold; color: #0f172a;">{{ $order->customer_name }}</div>
            <div style="color: #64748b;">{{ $order->customer_phone }}</div>
        </div>
        <div style="text-align: left;">
            <strong style="color: #475569;">حالة الدفع:</strong>
            <div><span style="background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">تم الدفع بالكامل</span></div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الرقم التسلسلي (SN)</th>
                <th>السعر المفرد</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <!-- تم تعديل الاستدعاء ليطابق orderItems بدلاً من items -->
            @foreach($order->orderItems as $item)
                <tr>
                    <td style="font-weight: 600;">{{ $item->product->name ?? 'منتج' }}</td>
                    <td>
                        <!-- تم تعديل الاستدعاء ليطابق productSerial بدلاً من serial -->
                        @if($item->productSerial)
                            <span class="serial-tag">{{ $item->productSerial->serial_number }}</span>
                        @else
                            <span style="color: #94a3b8;">غير محدد</span>
                        @endif
                    </td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td style="font-weight: bold;">${{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: left; border-top: 2px solid #f1f5f9; padding-top: 15px;">
        <span style="font-size: 1.1rem; color: #475569;">المبلغ الإجمالي الكلي:</span>
        <span style="font-size: 1.6rem; font-weight: 800; color: #10b981; margin-right: 15px;">${{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

</body>
</html>