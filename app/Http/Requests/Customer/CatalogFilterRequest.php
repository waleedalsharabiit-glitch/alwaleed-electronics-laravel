<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CatalogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'      => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id'    => ['nullable', 'integer', 'exists:brands,id'],
            'sort'        => ['nullable', 'string', 'in:price_asc,price_desc,latest'],
        ];
    }
}