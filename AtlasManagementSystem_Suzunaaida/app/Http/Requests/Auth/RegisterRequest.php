<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'under_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'mail_address' => 'required|string|email|max:100|unique:users,mail_address',
            'sex' => 'required|in:男性,女性',
            'old_year' => 'required|integer|min:2000|max:' . date('Y'),
            'old_month' => 'required|integer|min:1|max:12',
            'old_day' => [
                'required',
                'integer',
                'min:1',
                'max:31',
                function ($attribute, $value, $fail) {
                    $date = sprintf('%04d-%02d-%02d', request('old_year'), request('old_month'), $value);
                    if (!strtotime($date)) {
                        $fail('正しい日付を入力してください。');
                    }
                }
            ],
            'role' => 'required|in:講師(国語),講師(数学),教師(英語),生徒',
            'password' => 'required|string|min:8|max:30|confirmed',
            'password_confirmation' => 'required|string|min:8|max:30',
        ];
    }

    public function messages()
    {
        return [
            'over_name.required' => '姓を入力してください。',
            'over_name.max' => '姓は10文字以下で入力してください。',
            'under_name.required' => '名を入力してください。',
            'under_name.max' => '名は10文字以下で入力してください。',
            'over_name_kana.required' => 'セイを入力してください。',
            'over_name_kana.regex' => 'セイはカタカナで入力してください。',
            'over_name_kana.max' => 'セイは30文字以下で入力してください。',
            'under_name_kana.required' => 'メイを入力してください。',
            'under_name_kana.regex' => 'メイはカタカナで入力してください。',
            'under_name_kana.max' => 'メイは30文字以下で入力してください。',
            'mail_address.required' => 'メールアドレスを入力してください。',
            'mail_address.email' => '有効なメールアドレスを入力してください。',
            'mail_address.unique' => 'このメールアドレスは既に登録されています。',
            'mail_address.max' => 'メールアドレスは100文字以下で入力してください。',
            'sex.required' => '性別を選択してください。',
            'sex.in' => '性別は「男性」または「女性」を選択してください。',
            'old_year.required' => '生年月日の年を入力してください。',
            'old_year.min' => '2000年以降の年を入力してください。',
            'old_year.max' => '今年以前の年を入力してください。',
            'old_month.required' => '生年月日の月を入力してください。',
            'old_month.min' => '生年月日の月は1以上を入力してください。',
            'old_month.max' => '生年月日の月は12以下を入力してください。',
            'old_day.required' => '生年月日の日を入力してください。',
            'old_day.min' => '生年月日の日は1以上を入力してください。',
            'old_day.max' => '生年月日の日は31以下を入力してください。',
            'role.required' => '役職を選択してください。',
            'role.in' => '正しい役職を選択してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以下で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
            'password_confirmation.required' => 'パスワード確認を入力してください。',
        ];
    }
}
