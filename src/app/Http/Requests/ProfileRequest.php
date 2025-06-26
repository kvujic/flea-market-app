<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => ['nullable','mimes:jpeg,png', 'max:20480'],
            'name' => ['required', 'max:20'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['nullable', 'string']
        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes' => '画像は.jpegまたは.png形式でアップロードしてください',
            'profile_image.max' => '画像は20MBまでの大きさでアップロードしてください',
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフン込みの８文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
