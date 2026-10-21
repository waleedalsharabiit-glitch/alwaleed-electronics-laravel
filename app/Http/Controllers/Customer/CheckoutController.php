<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {}

    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'سلة التسوق فارغة.');
        }

        $total = $this->cartService->getTotal();

        return view('store.checkout.index', compact('cart', 'total'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'سلة التسوق فارغة.');
        }

        $cartItems = array_map(function ($item) {
            return [
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
            ];
        }, array_values($cart));

        try {
            $order = $this->checkoutService->processCheckout(
                $request->validated(),
                $cartItems
            );

            // تفريغ السلة بعد تمام العملية بنجاح
            $this->cartService->clear();

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', "تم تقديم طلبك بنجاح! رقم الفاتورة: {$order->order_number}");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}