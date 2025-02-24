<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'under_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'mail_address' => 'required|email|max:100|unique:users,email',
            'sex' => ['required', Rule::in(['男性', '女性', 'その他'])],
            'old_year' => 'required|integer|min:2000|max:' . date('Y'),
            'old_month' => 'required|integer|min:1|max:12',
            'old_day' => 'required|integer|min:1|max:31',
            'role' => ['required', Rule::in(['講師(国語)', '講師(数学)', '教師(英語)', '生徒'])],
            'password' => 'required|string|min:8|max:30|confirmed',
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'over_name.required' => '姓は必須です。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.max' => '姓は10文字以内で入力してください。',

            'under_name.required' => '名は必須です。',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.max' => '名は10文字以内で入力してください。',

            'over_name_kana.required' => '姓(カナ)は必須です。',
            'over_name_kana.string' => '姓(カナ)は文字列で入力してください。',
            'over_name_kana.max' => '姓(カナ)は30文字以内で入力してください。',
            'over_name_kana.regex' => '姓(カナ)はカタカナのみで入力してください。',

            'under_name_kana.required' => '名(カナ)は必須です。',
            'under_name_kana.string' => '名(カナ)は文字列で入力してください。',
            'under_name_kana.max' => '名(カナ)は30文字以内で入力してください。',
            'under_name_kana.regex' => '名(カナ)はカタカナのみで入力してください。',

            'mail_address.required' => 'メールアドレスは必須です。',
            'mail_address.email' => '有効なメールアドレスを入力してください。',
            'mail_address.unique' => 'このメールアドレスは既に使用されています。',
            'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',

            'sex.required' => '性別は必須です。',
            'sex.in' => '性別は「男性」「女性」「その他」のいずれかを選択してください。',

            'old_year.required' => '生年は必須です。',
            'old_year.integer' => '生年は数値で入力してください。',
            'old_year.min' => '生年は2000年以降である必要があります。',
            'old_year.max' => '生年は現在の年以下である必要があります。',

            'old_month.required' => '生月は必須です。',
            'old_month.integer' => '生月は数値で入力してください。',
            'old_month.min' => '生月は1以上である必要があります。',
            'old_month.max' => '生月は12以下である必要があります。',

            'old_day.required' => '生日は必須です。',
            'old_day.integer' => '生日は数値で入力してください。',
            'old_day.min' => '生日は1以上である必要があります。',
            'old_day.max' => '生日は31以下である必要があります。',

            'role.required' => '役割は必須です。',
            'role.in' => '役割は「講師(国語)」「講師(数学)」「教師(英語)」「生徒」のいずれかを選択してください。',

            'password.required' => 'パスワードは必須です。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上である必要があります。',
            'password.max' => 'パスワードは30文字以内で入力してください。',
            'password.confirmed' => 'パスワードの確認が一致しません。',
        ];
    }
}
