<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarrantyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $warrantyId = $this->route('warranty') ? $this->route('warranty')->id : null;

        return [
            'title'           => ['required', 'string', 'max:255', Rule::unique('warranties', 'title')->ignore($warrantyId)],
            'duration_months'=> ['required', 'integer', 'min:1', 'max:120'],
            'terms'          => ['nullable', 'string', 'max:2000'],
            'coverage_type'  => ['required', 'string', Rule::in(['full', 'hardware_only', 'maintenance_only'])],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'           => 'اسم وثيقة الضمان مطلوب.',
            'title.unique'             => 'اسم وثيقة الضمان موجود مسبقاً.',
            'duration_months.required'=> 'مدة الضمان بالأشهر مطلوبة.',
            'duration_months.integer' => 'مدة الضمان يجب أن تكون رقماً صحيحاً.',
            'duration_months.min'     => 'الحد الأدنى لمدّة الضمان هو شهر واحد.',
            'coverage_type.required'  => 'يرجى تحديد نوع تغطية الضمان.',
            'coverage_type.in'        => 'نوع التغطية المختار غير صالح.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
        ]);
    }
}