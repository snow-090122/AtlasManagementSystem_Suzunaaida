<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

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

  function render()
  {
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay()
  {
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd)
  {
    $html = [];

    $userId = auth()->id();
    $reservedParts = [];

    foreach ([1, 2, 3] as $part) {
      $reserve = ReserveSettings::with('users')
        ->where('setting_reserve', $ymd)
        ->where('setting_part', $part)
        ->first();

      if ($reserve && $reserve->users->contains('id', $userId)) {
        $reservedParts[] = $part;
      }
    }

    $html[] = '<div class="text-left">';

    foreach ([1, 2, 3] as $part) {
      $reserve = ReserveSettings::withCount('users')
        ->where('setting_reserve', $ymd)
        ->where('setting_part', $part)
        ->first();

      $count = $reserve ? $reserve->users_count : 0;

      $link = route('calendar.admin.detail', ['date' => $ymd, 'part' => $part]);

      if (in_array($part, $reservedParts)) {
        $html[] = '<p class="day_part m-0 pt-1 text-danger">
                <a href="' . $link . '" style="color: red; text-decoration: underline;">
                  リモ' . $part . '部 予約済 ' . $count . '
                </a>
              </p>';
      } else {
        $html[] = '<p class="day_part m-0 pt-1">
                <a href="' . $link . '" style="color: blue; text-decoration: underline;">
                  リモ' . $part . '部 ' . $count . '
                </a>
              </p>';
      }
    }


    $html[] = '</div>';
    return implode("", $html);
  }


  function onePartFrame($day)
  {
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if ($one_part_frame) {
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    } else {
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day)
  {
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if ($two_part_frame) {
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    } else {
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day)
  {
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if ($three_part_frame) {
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    } else {
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment()
  {
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
