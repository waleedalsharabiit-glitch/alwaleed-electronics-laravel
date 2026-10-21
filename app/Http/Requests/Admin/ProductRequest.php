<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name'                => ['required', 'string', 'max:255'],
            'sku'                 => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'category_id'         => ['required', 'exists:categories,id'],
            'brand_id'            => ['required', 'exists:brands,id'],
            'warranty_id'         => ['nullable', 'exists:warranties,id'],
            'price'               => ['required', 'numeric', 'min:0'],
            'sale_price'          => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_quantity'      => ['required', 'integer', 'min:0'],
            'critical_stock_alert'=> ['required', 'integer', 'min:0'],
            'image'               => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description'         => ['nullable', 'string'],
            'specs'               => ['nullable', 'array'],
            'specs.*.key'         => ['required_with:specs.*.value', 'string'],
            'specs.*.value'       => ['required_with:specs.*.key', 'string'],
            'is_active'           => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                 => 'اسم الجهاز مطلوب.',
            'sku.required'                  => 'رمز SKU الفريد مطلوب.',
            'sku.unique'                    => 'رمز SKU هذا مستخدم لجهاز آخر.',
            'category_id.required'          => 'يرجى اختيار القسم.',
            'brand_id.required'             => 'يرجى اختيار الماركة.',
            'price.required'                => 'سعر الجهاز مطلوب.',
            'sale_price.lt'                 => 'سعر الخصم يجب أن يكون أقل من السعر الأصلي.',
            'stock_quantity.required'       => 'الكمية المتوفرة مطلوبة.',
            'critical_stock_alert.required' => 'حد المخزون الحرج مطلوب للتنبيهات.',
            'image.image'                   => 'الملف المرفق يجب أن يكون صورة.',
            'image.max'                     => 'حجم الصورة لا يتجاوز 2 ميجابايت.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
        ]);
    }
}