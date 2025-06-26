<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->route()->getName() === 'purchase.address.update') {
            return [
                'shipping_postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
                'shipping_address' => ['required'],
            ];
        }

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
        ];
    }
}
