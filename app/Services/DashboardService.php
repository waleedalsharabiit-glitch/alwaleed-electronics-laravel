<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\User;
use App\Models\Warranty;

class DashboardService
{
    /**
     * جلب الإحصائيات العامة للنظام.
     */
    public function getMainStats(): array
    {
        return [
            'total_products'   => Product::count(),
            'total_stock'      => Product::sum('stock_quantity') ?? 0,
            'total_categories' => Category::count(),
            'total_brands'     => Brand::count(),
            'total_users'      => User::where('role', 'user')->count(),
            'active_warranties' => Warranty::count(),
        ];
    }

    /**
     * جلب حالات الأرقام التسلسلية.
     */
    public function getSerialsStats(): array
    {
        return [
            'available' => ProductSerial::where('status', 'available')->count(),
            'sold'      => ProductSerial::where('status', 'sold')->count(),
            'defective' => ProductSerial::where('status', 'defective')->count(),
        ];
    }

    /**
     * جلب أحدث المنتجات المضافة.
     */
    public function getRecentProducts(int $limit = 6)
    {
        return Product::with(['category', 'brand', 'warranty'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * جلب المنتجات ذات المخزون المنخفض.
     */
    public function getLowStockProducts(int $threshold = 5, int $limit = 5)
    {
        return Product::where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->take($limit)
            ->get();
    }
}