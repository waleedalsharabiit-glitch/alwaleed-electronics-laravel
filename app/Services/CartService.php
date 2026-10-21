<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    public function add(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }
    }

    public function remove(int $productId): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}