<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            \Log::debug('予約データ:', $reserveDays); // ←ログ追加

            foreach ($reserveDays as $key => $value) {
                \Log::debug("予約処理: 日付={$key}, 部={$value}");

                $reserve_settings = ReserveSettings::where('setting_reserve', $key)
                    ->where('setting_part', $value)
                    ->first();

                if (!$reserve_settings) {
                    \Log::debug("❌ 該当予約枠なし: {$key} - {$value}");
                    continue;
                }

                \Log::debug("✅ 該当予約枠あり: ID={$reserve_settings->id}");

                $reserve_settings->decrement('limit_users');
                \Log::debug("💡 Auth::id() = " . Auth::id());
                \Log::debug("💡 Auth::user() = " . Auth::user());

                $reserve_settings->users()->attach(Auth::user()->id);


                \Log::debug("✅ ユーザーID " . Auth::id() . " を予約枠に紐付けました");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("予約失敗: " . $e->getMessage());
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

}
