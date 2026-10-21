<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand') ? $this->route('brand')->id : null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($brandId)],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الماركة التجارية مطلوب.',
            'name.unique'   => 'هذه الماركة مسجلة من قبل.',
            'logo.image'    => 'ملف الشعار يجب أن يكون صورة.',
            'logo.max'      => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}