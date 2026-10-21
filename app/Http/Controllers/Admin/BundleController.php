<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BundleRequest;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\BundleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BundleController extends Controller
{
    public function __construct(protected BundleService $bundleService) {}

    public function index(): View
    {
        $bundles = $this->bundleService->getAllBundles();
        $products = Product::select(['id', 'name', 'price', 'stock_quantity'])->get();

        return view('Admin.bundles.index', compact('bundles', 'products'));
    }

    public function store(BundleRequest $request): RedirectResponse
    {
        $this->bundleService->createBundle($request->validated());

        return redirect()->route('admin.bundles.index')
            ->with('success', 'تم إنشاء حزمة العرض المجمعة بنجاح.');
    }

    public function toggleStatus(Bundle $bundle): RedirectResponse
    {
        $this->bundleService->toggleStatus($bundle);

        return redirect()->route('admin.bundles.index')
            ->with('success', 'تم تغيير حالة تفعيل الحزمة الترويجية.');
    }

    public function destroy(Bundle $bundle): RedirectResponse
    {
        $this->bundleService->deleteBundle($bundle);

        return redirect()->route('admin.bundles.index')
            ->with('success', 'تم حذف الحزمة الترويجية بنجاح.');
    }
}