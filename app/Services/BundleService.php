<?php

namespace App\Services;

use App\Models\Bundle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BundleService
{
    public function getAllBundles()
    {
        return Bundle::with(['products' => function($q) {
            $q->withPivot('quantity');
        }])->latest()->paginate(10);
    }

    public function createBundle(array $data): Bundle
    {
        return DB::transaction(function () use ($data) {
            $bundle = Bundle::create([
                'name'        => $data['name'],
                'slug'        => !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'is_active'   => $data['is_active'] ?? true,
            ]);

            $this->syncBundleProducts($bundle, $data['products']);

            return $bundle;
        });
    }

    public function updateBundle(Bundle $bundle, array $data): Bundle
    {
        return DB::transaction(function () use ($bundle, $data) {
            $bundle->update([
                'name'        => $data['name'],
                'slug'        => !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'is_active'   => $data['is_active'] ?? true,
            ]);

            $this->syncBundleProducts($bundle, $data['products']);

            return $bundle;
        });
    }

    public function toggleStatus(Bundle $bundle): bool
    {
        return $bundle->update(['is_active' => !$bundle->is_active]);
    }

    public function deleteBundle(Bundle $bundle): bool
    {
        return DB::transaction(function () use ($bundle) {
            $bundle->products()->detach();
            return $bundle->delete();
        });
    }

    private function syncBundleProducts(Bundle $bundle, array $products): void
    {
        $attachData = [];
        foreach ($products as $item) {
            if (!empty($item['product_id'])) {
                $attachData[$item['product_id']] = ['quantity' => $item['quantity'] ?? 1];
            }
        }
        $bundle->products()->sync($attachData);
    }
}