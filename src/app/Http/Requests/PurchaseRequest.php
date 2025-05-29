<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => ['required'],
            'shipping_postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postal_code.required' => '郵便番号を入力してください',
            'shipping_postal_code.regex' => '郵便番号はハイフン込みの８文字で入力してください',
            'shipping_address.required' => '住所を入力してください',
        ]
    }
}
