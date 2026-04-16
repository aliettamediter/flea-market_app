<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => 'required|string|max:100',
            'description'   => 'required|string|max:255',
            'price'         => 'required|integer|min:0',
            'category_id'   => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'condition_id'  => 'required|exists:conditions,id',
            'image'         => 'required|image|mimes:jpeg,png',
            'brand'         => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => '商品名を入力してください',
            'description.required'  => '商品の説明を入力してください',
            'description.max'       => '商品の説明は255文字以内で入力してください',
            'price.required'        => '価格を入力してください',
            'price.integer'         => '価格は整数で入力してください',
            'price.min'             => '価格は0円以上で入力してください',
            'category_id.required'  => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'image.required'        => '画像をアップロードしてください',
            'image.image'           => '画像ファイルをアップロードしてください',
            'image.mimes'           => '画像ファイルはjpeg, pngのいずれかをアップロードしてください',
        ];
    }
}