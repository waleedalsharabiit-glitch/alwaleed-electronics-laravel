<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $preOrderId = $this->route('pre_order') ? $this->route('pre_order')->id : null;

        return [
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'product_id'     => ['required', 'exists:products,id'],
            'quantity'       => ['required', 'integer', 'min:1'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'expected_date'  => ['required', 'date', 'after:today'],
            'status'         => ['required', Rule::in(['pending', 'confirmed', 'arrived', 'delivered', 'cancelled'])],
            'notes'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required'  => 'اسم العميل مطلوب.',
            'customer_phone.required' => 'رقم هاتف العميل مطلوب.',
            'product_id.required'     => 'يرجى تحديد الجهاز المطلوبة حزمه.',
            'quantity.min'            => 'الكمية المحجوزة يجب أن تكون 1 على الأقل.',
            'deposit_amount.required' => 'مبلغ الدفعة المقدمة مطلوب.',
            'expected_date.after'     => 'تاريخ الوصول المتوقع يجب أن يكون في المستقبل.',
            'status.required'         => 'حالة الحجز مطلوبة.',
        ];
    }
}