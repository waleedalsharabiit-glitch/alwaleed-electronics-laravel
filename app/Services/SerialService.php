<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSerial;
use Illuminate\Support\Facades\DB;

class SerialService
{
    public function getFilteredSerials(?int $productId = null, ?string $status = null)
    {
        return ProductSerial::with('product')
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);
    }

    public function storeSerials(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $createdCount = 0;

            // 1. الإدخال المجمع (Bulk Injection)
            if (!empty($data['bulk_serials'])) {
                $serialsArray = array_filter(array_map('trim', explode("\n", $data['bulk_serials'])));
                
                foreach ($serialsArray as $sn) {
                    if (!empty($sn) && !ProductSerial::where('serial_number', $sn)->exists()) {
                        ProductSerial::create([
                            'product_id'    => $data['product_id'],
                            'serial_number' => $sn,
                            'status'        => $data['status'],
                            'notes'         => $data['notes'] ?? null,
                        ]);
                        $createdCount++;
                    }
                }
            } 
            // 2. الإدخال الفردي Single Entry
            elseif (!empty($data['serial_number'])) {
                ProductSerial::create([
                    'product_id'    => $data['product_id'],
                    'serial_number' => trim($data['serial_number']),
                    'status'        => $data['status'],
                    'notes'         => $data['notes'] ?? null,
                ]);
                $createdCount = 1;
            }

            // تحديث الكمية المتوفرة تلقائياً في الجدول الرئيسي للمنتج
            $this->syncProductStock($data['product_id']);

            return $createdCount;
        });
    }

    public function updateSerialStatus(ProductSerial $serial, string $status, ?string $notes = null): ProductSerial
    {
        $serial->update([
            'status' => $status,
            'notes'  => $notes ?? $serial->notes,
        ]);

        $this->syncProductStock($serial->product_id);

        return $serial;
    }

    public function deleteSerial(ProductSerial $serial): bool
    {
        $productId = $serial->product_id;
        $deleted = $serial->delete();
        
        $this->syncProductStock($productId);

        return $deleted;
    }

    private function syncProductStock(int $productId): void
    {
        $availableCount = ProductSerial::where('product_id', $productId)
            ->where('status', 'available')
            ->count();

        Product::where('id', $productId)->update(['stock_quantity' => $availableCount]);
    }
}