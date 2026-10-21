<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\FetchCatalogProductsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CatalogFilterRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(CatalogFilterRequest $request, FetchCatalogProductsAction $action): View
    {
        // تغيير اسم المتغير إلى الجمع ومطابقته للقالب Correct variable naming
        $products   = $action->execute($request->validated());
        $categories = Category::select(['id', 'name', 'slug'])->get();
        $brands     = Brand::select(['id', 'name', 'slug'])->get();

        // إرجاع ملف العرض الخاص بالكتالوج index بدلاً من show
        return view('catalog.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product): View
    {
        $product->loadMissing(['brand', 'category', 'warranty', 'serials']);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->select(['id', 'name', 'price', 'category_id', 'main_image'])
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}