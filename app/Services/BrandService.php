<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    public function getAllBrands()
    {
        return Brand::withCount('products')->latest()->get();
    }

    public function createBrand(array $data): Brand
    {
        $data['slug'] = Str::slug($data['name']);

        if (isset($data['logo']) && $data['logo']->isValid()) {
            $data['logo'] = $data['logo']->store('brands', 'public');
        }

        return Brand::create($data);
    }

    public function deleteBrand(Brand $brand): bool
    {
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        return $brand->delete();
    }
}