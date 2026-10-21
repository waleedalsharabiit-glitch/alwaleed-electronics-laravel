<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAllCategoriesWithParents()
    {
        return Category::with('parent', 'children')->withCount('products')->latest()->get();
    }

    public function getParentCategoriesOnly()
    {
        return Category::whereNull('parent_id')->get();
    }

    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category): bool
    {
        // نقل الأبناء إلى القسم الأب أو جعلهم رئيسيين قبل الحذف
        $category->children()->update(['parent_id' => $category->parent_id]);
        return $category->delete();
    }
}