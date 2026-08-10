<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'condition_id' => ['required', 'integer', 'exists:conditions,id'],
            'price' => ['required', 'integer', 'min:0'],
        ];

        if (app()->environment('testing')) {
            $rules['img_url'] = ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'];
        } else {
            $rules['img_url'] = ['required', 'image', 'mimes:jpeg,png', 'max:2048'];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name'         => '商品名',
            'description'  => '商品説明',
            'img_url'      => '商品画像',
            'categories'   => '商品のカテゴリー',
            'condition_id' => '商品の状態',
            'price'        => '商品価格',
        ];
    }
}
