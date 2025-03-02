<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * 認可設定
     */
    public function authorize(): bool
    {
        return true; // ここをtrueにすることで、誰でもこのリクエストを利用可能にする
    }

    /**
     * バリデーションルールの設定
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:250',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'コメントは必須項目です。',
            'comment.string' => 'コメントは文字列で入力してください。',
            'comment.max' => 'コメントは250文字以内で入力してください。',
        ];
    }
}
