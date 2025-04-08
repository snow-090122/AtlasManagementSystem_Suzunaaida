<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'under_name_kana' => 'required|string|max:30|regex:/^[ァ-ヶー]+$/u',
            'mail_address' => 'required|email|max:100|unique:users,mail_address',
            'sex' => ['required', Rule::in([1, 2, 3])],
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::today()->format('Y-m-d'),
                'after_or_equal:2000-01-01',
            ],
            'role' => ['required', Rule::in([1, 2, 3, 4])],
            'password' => 'required|string|min:8|max:30|confirmed',

        ];
    }


    public function messages(): array
    {
        return [
            'over_name.required' => '姓を入力してください。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.max' => '姓は10文字以下で入力してください。',
            'under_name.required' => '名を入力してください。',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.max' => '名は10文字以下で入力してください。',
            'over_name_kana.required' => '姓（カナ）を入力してください。',
            'over_name_kana.regex' => '姓（カナ）はカタカナのみ入力してください。',
            'under_name_kana.required' => '名（カナ）を入力してください。',
            'under_name_kana.regex' => '名（カナ）はカタカナのみ入力してください。',
            'mail_address.required' => 'メールアドレスを入力してください。',
            'mail_address.email' => '有効なメールアドレスを入力してください。',
            'mail_address.unique' => 'このメールアドレスはすでに登録されています。',
            'sex.required' => '性別を選択してください。',
            'sex.in' => '性別は「男性」「女性」「その他」から選んでください。',
            'birth_date.required' => '生年月日が未入力です。',
            'birth_date.date' => '生年月日が未入力です。',
            'birth_date.before_or_equal' => '生年月日は今日以前の日付を入力してください。',
            'birth_date.after_or_equal' => '生年月日は2000年1月1日以降の日付を入力してください。',
            'role.required' => '役職を選択してください。',
            'role.in' => '役職は「講師(国語)」「講師(数学)」「教師(英語)」「生徒」から選んでください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以下で入力してください。',
            'password.confirmed' => 'パスワードが確認用と一致しません。',
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->filled(['old_year', 'old_month', 'old_day'])) {
            $this->merge([
                'birth_date' => sprintf('%04d-%02d-%02d', $this->input('old_year'), $this->input('old_month'), $this->input('old_day'))
            ]);
        }
    }

}
