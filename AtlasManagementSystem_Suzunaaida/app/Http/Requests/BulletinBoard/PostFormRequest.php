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
            // メインカテゴリーが必須かつ `main_categories` テーブルに存在することを確認
            'post_category_id' => [
                'required',
                Rule::exists('main_categories', 'id')
            ],

            // サブカテゴリー（選択された場合は `sub_categories` に存在する必要あり）
            'sub_category_id' => [
                'nullable', // 選択しなくてもよい
                Rule::exists('sub_categories', 'id')
            ],

            // 投稿のタイトルと本文のバリデーション
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages()
    {
        return [
            'post_category_id.required' => 'カテゴリーを選択してください。',
            'post_category_id.exists' => '選択されたカテゴリーが無効です。',

            'sub_category_id.exists' => '選択されたサブカテゴリーが無効です。',

            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.string' => 'タイトルは文字列で入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',

            'post_body.required' => '本文は必ず入力してください。',
            'post_body.string' => '本文は文字列で入力してください。',
            'post_body.max' => '本文は2000文字以内で入力してください。',
        ];
    }
}
