@extends('layouts.store')

@section('title', 'إتمام الطلب - متجر الوليد')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-black text-slate-900 mb-8"><i class="fa-solid fa-credit-card text-blue-600 ml-2"></i> إتمام الشراء وتوليد الفاتورة</h1>

    <form action="{{ route('store.checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- بيانات العميل والتوصيل -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-6">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-4 text-base"><i class="fa-solid fa-user-check text-blue-600 ml-2"></i> بيانات المشتري</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-2">اسم العميل بالكامل <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required
                               placeholder="الاسم الثلاثي..."
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl p-3 outline-none focus:border-blue-500 transition">
                        @error('customer_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-2">رقم الهاتف التواصل <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required
                               placeholder="770000000"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl p-3 outline-none focus:border-blue-500 transition">
                        @error('customer_phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-2">ملاحظات الفاتورة (اختياري)</label>
                        <textarea name="notes" rows="3"
                                  placeholder="أي تعليمات أو ملاحظات تسليم..."
                                  class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl p-3 outline-none focus:border-blue-500 transition">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ملخص الفاتورة والسيريالات -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 h-fit space-y-6">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3">تفاصيل الفاتورة</h3>

                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 space-y-2 pr-1">
                    @foreach($cart as $item)
                        <div class="pt-2 flex justify-between items-center text-xs">
                            <div>
                                <h5 class="font-bold text-slate-800 line-clamp-1">{{ $item['name'] }}</h5>
                                <span class="text-slate-400">الكمية: {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</span>
                            </div>
                            <span class="font-black text-slate-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-2">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>الضمان والسيريالات:</span>
                        <span class="font-bold text-emerald-600"><i class="fa-solid fa-shield-halved ml-1"></i> حجز تلقائي</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex justify-between text-base font-black text-slate-900">
                        <span>المبلغ الإجمالي:</span>
                        <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-4 rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5">
                    تأكيد الشراء وحجز السيريالات <i class="fa-solid fa-check-circle mr-1"></i>
                </button>
            </div>

        </div>
    </form>
</div>
@endsection