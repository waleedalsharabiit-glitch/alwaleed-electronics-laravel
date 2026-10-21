@extends('layouts.store')

@section('title', 'تفاصيل الفاتورة ' . ($order->order_number ?? '#'.$order->id))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden p-6 md:p-10 space-y-8">
        
        <!-- هيدر الفاتورة -->
        <div class="flex justify-between items-start border-b border-slate-100 pb-6 flex-wrap gap-4">
            <div>
                <span class="text-xs text-slate-400 font-bold block">فاتورة شراء رقم</span>
                <h1 class="text-2xl font-black text-slate-900">{{ $order->order_number ?? '#INV-'.$order->id }}</h1>
                <p class="text-xs text-slate-500 mt-1">تاريخ الإصدار: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="text-left">
                <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full mb-2">
                    <i class="fa-solid fa-check-circle ml-1"></i> عملية مكتملة
                </span>
                <p class="text-xl font-black text-blue-600">${{ number_format($order->total_amount, 2) }}</p>
            </div>
        </div>

        <!-- بيانات العميل -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl text-xs">
            <div>
                <span class="text-slate-400 block font-bold">اسم المشتري:</span>
                <span class="font-bold text-slate-800 text-sm">{{ $order->customer_name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-bold">رقم الهاتف:</span>
                <span class="font-bold text-slate-800 text-sm">{{ $order->customer_phone }}</span>
            </div>
        </div>

        <!-- قائمة الأجهزة والسيريالات وحالة الضمان -->
        <div class="space-y-4">
            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-emerald-600"></i> الأجهزة المشتراة وتأمين الضمان السريالي
            </h3>

            <div class="space-y-3">
                @foreach($order->orderItems as $item)
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-blue-200 transition">
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $item Gilbert ?? $item->product->name }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <span>السعر: <strong>${{ number_format($item->unit_price, 2) }}</strong></span>
                                <span>•</span>
                                <span>الضمان: <strong>{{ $item->product->warranty->name ?? 'قياسي' }} ({{ $item->product->warranty->duration_months ?? 12 }} شهر)</strong></span>
                            </div>
                        </div>

                        <!-- كرت السيريال نمبر -->
                        <div class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs flex items-center gap-3">
                            <div>
                                <span class="text-[10px] text-slate-400 block">الرقم التسلسلي (S/N)</span>
                                <span class="font-mono font-bold tracking-wider text-emerald-400">
                                    {{ $item->productSerial->serial_number ?? 'غير مسجل' }}
                                </span>
                            </div>
                            <i class="fa-solid fa-barcode text-slate-500 text-xl"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
                ← العودة لوحة التحكم
            </a>
            <button onclick="window.print()" class="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                <i class="fa-solid fa-print ml-1"></i> طباعة الفاتورة
            </button>
        </div>

    </div>
</div>
@endsection