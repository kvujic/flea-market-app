<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExhibitionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'item_image' => ['required', 'mimes:jpeg,png', 'max:20480'],
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
            'description.max' => '商品説明は255文字以内で入力してください',
            'item_image.required' => '商品画像をアップロードしてください',
            'item_image.mimes' => '商品画像は.jpegまたは.png形式でアップロードしてください',
            'item_image.max' => '商品画像は20MBまでの大きさでアップロードしてください',
            'categories.required' => '商品のカテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値型で入力してください',
            'price.min' => '商品価格は０円以上で入力してください',
        ];
    }

    /* exchange the message when validation failed */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if (
            $errors->has('item_image') &&
            $errors->first('item_image') === 'The item image failed to upload.'
        ) {
            $errors->add('item_image', 'ファイル形式が正しくないか、アップロードに失敗しました。商品画像は.jpegまたは.png形式でアップロードしてください');
        }

        throw new HttpResponseException(
            redirect()->back()
            ->withErrors($errors)
            ->withInput()
        );
    }
}
