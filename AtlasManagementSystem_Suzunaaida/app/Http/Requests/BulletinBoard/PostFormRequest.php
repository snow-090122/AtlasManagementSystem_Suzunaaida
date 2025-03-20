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
        $rules = [
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ];

        if ($this->isMethod('post')) {
            $rules['sub_category_id'] = 'required|exists:sub_categories,id';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'sub_category_id.required' => 'カテゴリーを選択してください。',
            'sub_category_id.exists' => '選択されたカテゴリーは存在しません。',
            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.string' => 'タイトルは文字列である必要があります。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '投稿内容は必ず入力してください。',
            'post_body.string' => '投稿内容は文字列である必要があります。',
            'post_body.max' => '投稿内容は2000文字以内で入力してください。',
        ];
    }

}
