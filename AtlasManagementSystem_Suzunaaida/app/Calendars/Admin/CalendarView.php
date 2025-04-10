<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Users\User;

class CalendarView
{
  private $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  public function render()
  {
    $html = [];
    $html[] = '<table class="table table-bordered m-auto" style="table-layout: fixed; width: 100%;">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $weekdays = ['月', '火', '水', '木', '金', '土', '日'];
    foreach ($weekdays as $index => $day) {
      $class = '';
      if ($index === 5) {
        $class = 'saturday';
      } elseif ($index === 6) {
        $class = 'sunday';
      }
      $html[] = '<th class="border ' . $class . '" style="width: 14.28%;">' . $day . '</th>';
    }
    $html[] = '</tr>';
    $html[] = '</thead>';

    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $date = $day->everyDay();
        $toDay = $this->carbon->format("Y-m-d");

        // ➤ 土曜・日曜の class を取得
        $weekDay = Carbon::parse($date)->dayOfWeek;
        $colorClass = '';
        if ($weekDay === 6) {
          $colorClass = 'saturday';
        } elseif ($weekDay === 0) {
          $colorClass = 'sunday';
        }

        // ➤ <td> 出力
        if ($startDay <= $date && $toDay >= $date) {
          $html[] = '<td class="past-day border ' . $colorClass . '" data-date="' . $date . '">';
        } else {
          $html[] = '<td class="border ' . $day->getClassName() . '" data-date="' . $date . '">';
        }

        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay());
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';

    return implode("", $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
