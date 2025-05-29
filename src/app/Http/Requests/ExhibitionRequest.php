<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required', 'max:225'],
            'item_image' => ['required', 'mimes:jpeg,png'],
            'categories' => ['required'],
            'condition_id' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は225文字以内で入力してください',
            'item_image.required' => '商品画像をアップロードしてください',
            'item_image.mines' => '商品画像は.jpegまたは.png形式でアップロードしてください',
            'categories.required' => '商品のカテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値型で入力してください',
            'price.min' => '商品価格は０円以上で入力してください',
        ];
    }
}
