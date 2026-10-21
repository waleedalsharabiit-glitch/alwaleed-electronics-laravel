<?php

namespace App\Actions\Customer;

use App\Models\Product;
use App\Models\Category;
use App\Models\Bundle;

class FetchHomeDataAction
{
    public function execute(): array
    {
        return [
            'categories'     => Category::take(6)->get(),
            'latestProducts' => Product::with(['brand', 'category'])->latest()->take(8)->get(),
            'activeBundles'  => Bundle::where('is_active', true)->latest()->take(3)->get(),
        ];
    }
}