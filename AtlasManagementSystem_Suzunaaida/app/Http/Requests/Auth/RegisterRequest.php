<?php

namespace App\Http\Requests\Auth;

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
            'mail_address' => 'required|email|max:100|unique:users,mail_address',
            'sex' => ['required', 'integer', Rule::in([1, 2, 3])],
            'birth_date' => [
                'required',
                'date',
                'before:today -18 years', // 18歳未満は不可
            ],
            'role' => ['required', 'integer', Rule::in([1, 2, 3, 4])], // 整数のみに変更
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
            'sex.in' => '性別は「1（男性）」「2（女性）」「3（その他）」のいずれかを選択してください。',

            'birth_date.required' => '生年月日は必須です。',
            'birth_date.date' => '生年月日は有効な日付を入力してください。',
            'birth_date.before' => '18歳未満は登録できません。',

            'role.required' => '役割は必須です。',
            'role.in' => '役割は「1（国語教師）」「2（数学教師）」「3（英語教師）」「4（生徒）」のいずれかを選択してください。',

            'password.required' => 'パスワードは必須です。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上である必要があります。',
            'password.max' => 'パスワードは30文字以内で入力してください。',
            'password.confirmed' => 'パスワードの確認が一致しません。',
        ];
    }
}
