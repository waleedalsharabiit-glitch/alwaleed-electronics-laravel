<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name'      => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoryId)],
            'parent_id' => ['nullable', 'exists:categories,id', 'different:id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'اسم القسم مطلوب.',
            'name.unique'       => 'اسم القسم موجود بالفعل.',
            'parent_id.exists'  => 'القسم الأب المحدد غير موجود.',
            'parent_id.different'=> 'لا يمكن اختيار القسم ليكون أباً لنفسه.',
        ];
    }
}