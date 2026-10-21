<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brandService) {}

    public function index(): View
    {
        $brands = $this->brandService->getAllBrands();
        return view('Admin.brands.index', compact('brands'));
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        $this->brandService->createBrand($request->validated());

        return redirect()->route('admin.brands.index')->with('success', 'تم إضافة الماركة بنجاح.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->brandService->deleteBrand($brand);

        return redirect()->route('admin.brands.index')->with('success', 'تم حذف الماركة التجارية بنجاح.');
    }
}