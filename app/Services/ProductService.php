<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ProductService
{
    public function getAllProducts()
    {
        return Product::with(['category', 'brand', 'warranty'])
            ->withCount('serials')
            ->latest()
            ->paginate(15);
    }

    public function createProduct(array $data): Product
    {
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        // تحويل المواصفات الديناميكية إلى Key-Value JSON
        if (isset($data['specs']) && is_array($data['specs'])) {
            $formattedSpecs = [];
            foreach ($data['specs'] as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $formattedSpecs[$spec['key']] = $spec['value'];
                }
            }
            $data['specifications'] = $formattedSpecs;
        }

        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        if (isset($data['image']) && $data['image']->isValid()) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $data['image']->store('products', 'public');
        }

        if (isset($data['specs']) && is_array($data['specs'])) {
            $formattedSpecs = [];
            foreach ($data['specs'] as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $formattedSpecs[$spec['key']] = $spec['value'];
                }
            }
            $data['specifications'] = $formattedSpecs;
        }

        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product): bool
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }
}