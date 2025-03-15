<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
{
    /**
     * 認可設定
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールの設定
     */
    public function rules(): array
    {
        return [
            'main_category_id' => [
                'required',
                'exists:main_categories,id', // main_categoriesのidカラムを確認
            ],
            'sub_category' => [
                'required',
                'string',
                'max:60',
                Rule::unique('sub_categories', 'sub_category')
                    ->where(fn($query) => $query->where('main_category_id', $this->input('main_category_id'))),
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages()
    {
        return [
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
            'sub_category.required' => 'サブカテゴリー名を入力してください。',
            'sub_category.string' => 'サブカテゴリー名は文字列である必要があります。',
            'sub_category.max' => 'サブカテゴリー名は60文字以内で入力してください。',
            'sub_category.unique' => 'このサブカテゴリーはすでに登録されています。',
        ];
    }
}
