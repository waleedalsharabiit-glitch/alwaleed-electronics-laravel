<?php

namespace App\Services;

use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PreOrderService
{
    public function getAllPreOrders(?string $status = null)
    {
        return PreOrder::with('product')
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(12);
    }

    public function createPreOrder(array $data): PreOrder
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            
            // حساب المتبقي تلقائياً
            $totalPrice = $product->price * $data['quantity'];
            $data['total_price'] = $totalPrice;
            $data['remaining_amount'] = max(0, $totalPrice - $data['deposit_amount']);
            $data['reference_code'] = 'PRE-' . strtoupper(uniqid());

            return PreOrder::create($data);
        });
    }

    public function updateStatus(PreOrder $preOrder, string $status, ?string $notes = null): PreOrder
    {
        $preOrder->update([
            'status' => $status,
            'notes'  => $notes ?? $preOrder->notes,
        ]);

        return $preOrder;
    }

    public function deletePreOrder(PreOrder $preOrder): bool
    {
        return $preOrder->delete();
    }
}