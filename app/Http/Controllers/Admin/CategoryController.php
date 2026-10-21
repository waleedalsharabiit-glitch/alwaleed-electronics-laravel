<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(): View
    {
        $categories       = $this->categoryService->getAllCategoriesWithParents();
        $parentCategories = $this->categoryService->getParentCategoriesOnly();

        return view('Admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة القسم بنجاح.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث البيانات بنجاح.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->deleteCategory($category);

        return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم بنجاح.');
    }
}