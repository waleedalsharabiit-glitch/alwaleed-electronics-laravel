<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * السماح لجميع الزوار بإرسال الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق من صحة البيانات.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * رسائل الخطأ باللغة العربية.
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'يرجى إدخال الاسم بالكامل.',
            'email.required'    => 'يرجى إدخال البريد الإلكتروني.',
            'email.email'       => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.unique'      => 'هذا البريد الإلكتروني مُسجّل لدينا بالفعل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min'      => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'password.confirmed'=> 'تأكيد كلمة المرور غير متطابق.',
        ];
    }
}