<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostFormRequest extends FormRequest
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
        return [
            'post_category_id' => 'required|exists:sub_categories,id',
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',

            // メインカテゴリーのバリデーション
            'main_category_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('main_categories', 'name')
            ],

            // メインカテゴリー ID のバリデーション
            'main_category_id' => [
                'required',
                Rule::exists('main_categories', 'id')
            ],

            // サブカテゴリーのバリデーション
            'sub_category_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sub_categories', 'name')
            ],
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages()
    {
        return [
            'post_category_id.required' => 'カテゴリーを選択してください。',
            'post_category_id.exists' => '選択されたカテゴリーが存在しません。',
            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.string' => 'タイトルは文字列である必要があります。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '内容は必ず入力してください。',
            'post_body.string' => '内容は文字列である必要があります。',
            'post_body.max' => '内容は2000文字以内で入力してください。',

            'main_category_name.required' => 'メインカテゴリーを必ず入力してください。',
            'main_category_name.unique' => 'このメインカテゴリー名はすでに登録されています。',

            'main_category_id.exists' => '選択されたメインカテゴリーが無効です。',

            'sub_category_name.required' => 'サブカテゴリーを必ず入力してください。',
            'sub_category_name.unique' => 'このサブカテゴリー名はすでに登録されています。',
        ];
    }
}
