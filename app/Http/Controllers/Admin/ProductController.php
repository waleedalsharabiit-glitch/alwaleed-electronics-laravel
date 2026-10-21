<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warranty;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function index(): View
    {
        $products = $this->productService->getAllProducts();
        return view('Admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::select(['id', 'name'])->get();
        $brands     = Brand::select(['id', 'name'])->get();
        $warranties = Warranty::where('is_active', true)->select(['id', 'title', 'duration_months'])->get();

        return view('Admin.products.create', compact('categories', 'brands', 'warranties'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
 
        $this->productService->createProduct($request->validated());

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة الجهاز بنجاح.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::select(['id', 'name'])->get();
        $brands     = Brand::select(['id', 'name'])->get();
        $warranties = Warranty::where('is_active', true)->select(['id', 'title', 'duration_months'])->get();

        return view('Admin.products.edit', compact('product', 'categories', 'brands', 'warranties'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->updateProduct($product, $request->validated());

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث بيانات الجهاز بنجاح.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('admin.products.index')->with('success', 'تم حذف الجهاز بنجاح.');
    }
}