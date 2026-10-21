<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index(): View
    {
        $cart  = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        return view('store.cart.index', compact('cart', 'total'));
    }

    public function add(AddToCartRequest $request): RedirectResponse
    {
        $this->cartService->add(
            $request->validated('product_id'),
            $request->validated('quantity')
        );

        return back()->with('success', 'تمت إضافة المنتج إلى السلة بنجاح.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cartService->update($id, $validated['quantity']);

        return back()->with('success', 'تم تحديث الكمية.');
    }

    public function remove(int $id): RedirectResponse
    {
        $this->cartService->remove($id);

        return back()->with('success', 'تم إزالة المنتج من السلة.');
    }
}