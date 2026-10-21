<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function storeFront()
    {
        $products = Product::where('stock_quantity', '>', 0)->latest()->paginate(8);
        return view('store.index', compact('products'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'cart'           => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity'   => 'required|integer|min:1',
        ]);

        try {
            $order = $this->checkoutService->processCheckout(
                [
                    'customer_name'  => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                ],
                $validated['cart']
            );

            return redirect()->route('store.invoice', $order->id)
                ->with('success', 'تم إتمام الشراء بنجاح وتوليد الفاتورة التلقائية!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'items.serial');
        return view('store.invoice', compact('order'));
    }
}