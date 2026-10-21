<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSerial;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    public function processCheckout(array $customerData, array $cartItems): Order
    {
        return DB::transaction(function () use ($customerData, $cartItems) {
            $totalAmount = 0;
            $itemsToInsert = [];

            foreach ($cartItems as $item) {
                // قفل السجل لضمان الحماية ضد السباق Race Conditions
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new Exception("الكمية المطلوبة غير متوفرة للمنتج: {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                // حجز السيريالات المتاحة للقطعة
                $serials = ProductSerial::where('product_id', $product->id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->take($item['quantity'])
                    ->get();

                $itemsToInsert[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                    'serials' => $serials
                ];
            }

            // إنشاء أمر البيع الرئيسي
            $order = Order::create([
                'order_number'   => 'INV-' . strtoupper(uniqid()),
                'customer_name'  => $customerData['customer_name'],
                'customer_phone' => $customerData['customer_phone'],
                'total_amount'   => $totalAmount,
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'notes'          => $customerData['notes'] ?? null,
            ]);

            // إدراج التفاصيل وتحديث السيريالات والمخزون
            foreach ($itemsToInsert as $data) {
                foreach ($data['serials'] as $serial) {
                    $serial->update(['status' => 'sold']);

                    OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $data['product']->id,
                        'product_serial_id' => $serial->id,
                        'quantity'          => 1,
                        'unit_price'        => $data['unit_price'],
                        'subtotal'          => $data['unit_price'],
                    ]);
                }

                // تحديث كمية المخزون
                $data['product']->decrement('stock_quantity', $data['quantity']);
            }

            return $order;
        });
    }
}