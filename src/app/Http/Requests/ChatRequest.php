<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'max:400'],
            'item_image' => ['mimes:jpeg,png', 'max:20480']
        ];
    }

    public function messages()
    {
        return [[
            'message.required' => '本文を入力してください',
            'message.max' => '本文は４００文字以内で入力してください',
            'item_image' => '「.png」または「.jpeg」形式でアップロードしてください'
        ]];
    }
}
