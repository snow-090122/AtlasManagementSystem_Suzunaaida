<?php
namespace App\Calendars\General;

use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;
use Auth;

class CalendarWeekDay
{
  protected $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  function getClassName()
  {
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function pastClassName()
  {
    return;
  }

  /**
   * @return
   */

  function render()
  {
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function selectPart($ymd)
  {
    // 各部の予約設定と予約済人数を取得
    $one_setting = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
    $two_setting = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_setting = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    // 残枠数を計算（nullなら0に）
    $one_part_frame = $one_setting ? $one_setting->limit_users - $one_setting->users->count() : 0;
    $two_part_frame = $two_setting ? $two_setting->limit_users - $two_setting->users->count() : 0;
    $three_part_frame = $three_setting ? $three_setting->limit_users - $three_setting->users->count() : 0;

    $html = [];
    $html[] = '<select name="getPart[]" class="border-primary" style="width:70px; border-radius:5px;" form="reserveParts">';
    $html[] = '<option value="" selected></option>';

    if ($one_part_frame <= 0) {
      $html[] = '<option value="1" disabled>リモ1部(残り0枠)</option>';
    } else {
      $html[] = '<option value="1">リモ1部(残り' . $one_part_frame . '枠)</option>';
    }

    if ($two_part_frame <= 0) {
      $html[] = '<option value="2" disabled>リモ2部(残り0枠)</option>';
    } else {
      $html[] = '<option value="2">リモ2部(残り' . $two_part_frame . '枠)</option>';
    }

    if ($three_part_frame <= 0) {
      $html[] = '<option value="3" disabled>リモ3部(残り0枠)</option>';
    } else {
      $html[] = '<option value="3">リモ3部(残り' . $three_part_frame . '枠)</option>';
    }

    $html[] = '</select>';
    return implode('', $html);
  }

  function getDate()
  {
    return '<input type="hidden" value="' . $this->carbon->format('Y-m-d') . '" name="getData[]" form="reserveParts">';
  }

  function everyDay()
  {
    return $this->carbon->format('Y-m-d');
  }

  function authReserveDay()
  {
    return Auth::user()->reserveSettings->pluck('setting_reserve')->toArray();
  }

  function authReserveDate($reserveDate)
  {
    return Auth::user()->reserveSettings->where('setting_reserve', $reserveDate);
  }

}
