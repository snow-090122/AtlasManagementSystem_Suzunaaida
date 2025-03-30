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
    public function delete(Request $request)
    {

        DB::beginTransaction();
        try {
            $date = $request->input('date');
            $part = $request->input('part');

            \Log::debug("キャンセル処理: 日付={$date}, 部={$part}");

            $reserve_setting = ReserveSettings::where('setting_reserve', $date)
                ->where('setting_part', $part)
                ->first();
            \Log::debug("🧪 Auth::id() = " . Auth::id());
            \Log::debug("🧪 reserve_setting_id = " . optional($reserve_setting)->id);


            if (!$reserve_setting) {
                \Log::debug("❌ 該当予約枠が見つかりません: {$date} - {$part}");
                return redirect()->back()->with('error', '予約情報が見つかりませんでした。');
            }

            \Log::debug("削除対象: user_id=" . Auth::id() . ", reserve_setting_id=" . $reserve_setting->id);
            // 中間テーブルから予約解除
            DB::table('reserve_setting_users')
                ->where('user_id', Auth::id())
                ->where('reserve_setting_id', $reserve_setting->id)
                ->delete();

            // 枠を1つ戻す
            $reserve_setting->increment('limit_users');

            \Log::debug("✅ キャンセル完了: reserve_setting_id={$reserve_setting->id}");

            DB::commit();

            return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])
                ->with('success', '予約をキャンセルしました。');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("キャンセル失敗: " . $e->getMessage());
            return redirect()->back()->with('error', 'キャンセル処理に失敗しました。');
        }
    }

}
