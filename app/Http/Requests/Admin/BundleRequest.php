<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BundleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bundleId = $this->route('bundle') ? $this->route('bundle')->id : null;

        return [
            'name'                   => ['required', 'string', 'max:255'],
            'slug'                   => ['nullable', 'string', 'max:255', 'unique:bundles,slug,' . $bundleId],
            'description'            => ['nullable', 'string'],
            'price'                  => ['required', 'numeric', 'min:0'],
            'is_active'              => ['nullable', 'boolean'],
            'products'               => ['required', 'array', 'min:2'], // الحزمة تتطلب منتجين على الأقل
            'products.*.product_id'  => ['required', 'exists:products,id'],
            'products.*.quantity'    => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'اسم الحزمة الترويجية مطلوب.',
            'price.required'             => 'سعر العرض للحزمة مطلوب.',
            'products.required'          => 'يجب إضافة منتجات إلى الحزمة.',
            'products.min'               => 'يجب أن تحتوي الحزمة على منتجين على الأقل.',
            'products.*.product_id.exists' => 'أحد المنتجات المحددة غير موجود.',
            'products.*.quantity.min'    => 'الكمية لكل منتج يجب أن تكون 1 على الأقل.',
        ];
    }
}