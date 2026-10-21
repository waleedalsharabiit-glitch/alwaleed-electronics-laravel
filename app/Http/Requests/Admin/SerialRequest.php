<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SerialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serialId = $this->route('serial') ? $this->route('serial')->id : null;

        // في حال الإدخال الفردي أو الإدخال المجمع
        return [
            'product_id'    => ['required', 'exists:products,id'],
            'serial_number' => ['nullable', 'string', 'max:100', Rule::unique('product_serials', 'serial_number')->ignore($serialId)],
            'bulk_serials'  => ['nullable', 'string'], // يدعم إدخال عدة سيريال تفصل بينها أسطر
            'status'        => ['required', Rule::in(['available', 'sold', 'maintenance', 'damaged'])],
            'notes'         => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'    => 'يرجى تحديد الجهاز المرتبط بالسيريال.',
            'product_id.exists'      => 'الجهاز المحدد غير موجود بالمنظومة.',
            'serial_number.unique'   => 'الرقم التسلسلي مدخل مسبقاً لرقم قطعة آخر.',
            'status.required'        => 'حالة القطعة مطلوبة.',
            'status.in'              => 'حالة القطعة المحددة غير صالحة.',
        ];
    }
}