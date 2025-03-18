<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Subjects\Subject;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('auth.register.register', compact('subjects'));
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        // dd($request->all()); // 🔍 フォームの送信データを確認

        DB::beginTransaction();
        try {
            $birth_day = $request->birth_date;
            // dd($birth_day); // 🔍 `YYYY-MM-DD` 形式で正しく取得されているか確認

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);

            if ($request->role == 4 && !empty($request->subject)) {
                $user_get->subjects()->attach($request->subject);
            }

            DB::commit();
            return view('auth.login.login');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }

}
