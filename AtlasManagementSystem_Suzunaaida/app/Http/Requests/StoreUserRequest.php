<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\Return_;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regx:/^[ァ-ヶー]+$/u',
            'under_name_kana' => 'required|string|max:30|regx:/^[ァ-ヶー]+$/u',
            'mail_address' => 'required|string|max:100|unique:users,email',
            'sex' => ['required', Rule::in('男性', '女性', 'その他')],
            'old_year' => 'required|integer|min:2000|max:' . date('Y'),
            'old_month' => 'required|integer|min:1|max:12',
            'old_day' => 'required|integer|min:1|max:31',
            'role' => ['required', Rule::in(['講師（国語）', '講師（数学）', ' 教師（英語）', '生徒'])],
            'password' => 'required|string|min:8|max:30|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'over_name.required' => '姓は必須です。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.max' => '姓は10文字以内で入力してください。',

            'under_name.required' => '名は必須です。',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.max' => '名は10文字以内で入力してください。',

            'over_name_kana.required' => '姓（カナ）は必須です。',
            'over_name_kana.regex' => '姓（カナ）はカタカナのみで入力してください。',

            'under_name_kana.reruired' => '名（カナ）は必須です。',
            'under_name_kana.regex' => '名（カナ）はカタカナのみで入力してください。',

            'mail_address.required' => 'メールアドレスは必須です。',
            'mail_address.email' => '有効なメールアドレスを入力してください。',
            'mail_address.unique' => 'このメールアドレスは既に使用されています。',

            'sex.required' => '性別は必須です。',
            'sex.in' => '性別は「男性」「女性」「その他」いずれかを選択してください。',

            'old_year.required' => '生年は必須です。',
            'old_year.min' => '生年は2000年以降である必要があります。',
            'old_year.max' => '生年は現在の年以下である必要があります。',

            'old_month.required' => '生月は必須です。',
            'old_month.min' => '生月は1以上である必要があります。',
            'old_month.max' => '生月は12以下である必要があります。',

            'old_day.required' => '生日は必須です。',
            'old_day.min' => '生日は1以上である必要があります。',
            'old_day.max' => '生日は30以上である必要があります。',

            'role.required' => '役割は必須です。',
            'role.in' => '役割は「講師（国語）」「講師（数学）」「教師（英語）」「生徒」いずれかを選択してください。',

            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワーは8文字以上である必要があります。',
            'password.max' => 'パスワードは30文字以内である必要があります。',
            'password.confirmed' => 'パスワードの確認が一致しません。',
        ];
    }
}
