@extends('layouts.store')

@section('title', 'سلة التسوق')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-black text-slate-900 mb-8"><i class="fa-solid fa-cart-shopping text-blue-600 ml-2"></i> سلة الشراء</h1>

    @if(empty($cart))
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center shadow-sm">
            <i class="fa-solid fa-basket-shopping text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-lg font-bold text-slate-700">سلتك فارغة حالياً</h3>
            <a href="{{ route('store.catalog') }}" class="mt-4 inline-block bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs hover:bg-blue-700 transition">تصفح المنتجات</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- جدول عناصر السلة -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
                <div class="divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900 text-sm">{{ $item['name'] }}</h4>
                                <span class="text-xs text-blue-600 font-bold">${{ number_format($item['price'], 2) }}</span>
                            </div>

                            <!-- تحديث الكمية -->
                            <form action="{{ route('store.cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 bg-slate-50 border border-slate-200 text-center rounded-lg font-bold text-xs p-1 outline-none">
                                <button type="submit" class="text-xs bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg text-slate-600 font-bold">تحديث</button>
                            </form>

                            <!-- الإجمالي والحذف -->
                            <div class="text-left flex items-center gap-4">
                                <span class="font-black text-slate-900 text-sm">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                <form action="{{ route('store.cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ملخص الفاتورة -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 h-fit space-y-4">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3">ملخص الطلب</h3>
                <div class="flex justify-between text-xs text-slate-600">
                    <span>المجموع الفرعي:</span>
                    <span class="font-bold text-slate-900">${{ number_format($total, 2) }}</span>
                </div>
                <div class="border-t border-slate-100 pt-3 flex justify-between text-sm font-black text-slate-900">
                    <span>الإجمالي الكلي:</span>
                    <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('store.checkout') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3.5 rounded-xl block text-center shadow-lg shadow-blue-600/20 transition">
                    المتابعة لإتمام الطلب ←
                </a>
            </div>
        </div>
    @endif
</div>
@endsection