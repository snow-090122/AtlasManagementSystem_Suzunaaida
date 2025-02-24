<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Subjects\Subject;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // 生年月日を結合して `Y-m-d` 形式に変換
            $birth_day = sprintf('%04d-%02d-%02d', $request->old_year, $request->old_month, $request->old_day);

            // ユーザーを作成
            $user = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // 生徒（role = 4）の場合のみ subjects を関連付ける
            if ($request->role == 4 && !empty($request->subject)) {
                $user->subjects()->attach($request->subject);
            }

            DB::commit();

            // 登録完了後にログインページへリダイレクト
            return redirect()->route('login')->with('success', '登録が完了しました。');
        } catch (\Exception $e) {
            DB::rollback();

            // エラーメッセージとともに登録画面にリダイレクト
            return redirect()->route('register')->with('error', '登録処理に失敗しました。再度お試しください。');
        }
    }
}
