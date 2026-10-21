<?php

namespace App\Actions\Customer;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchCatalogProductsAction
{
    public function execute(array $filters): LengthAwarePaginator
    {
        $query = Product::with(['brand', 'category']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        match ($filters['sort'] ?? 'latest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }
}