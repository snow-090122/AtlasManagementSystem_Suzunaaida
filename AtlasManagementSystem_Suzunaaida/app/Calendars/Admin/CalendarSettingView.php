<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView
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
    $html[] = '<div class="calendar text-center">';
    $html[] = '<h2 class="calendar-title mb-4">' . $this->getTitle() . '</h2>';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $weekdays = ['月', '火', '水', '木', '金', '土', '日'];
    foreach ($weekdays as $index => $day) {
      $class = '';
      if ($index === 5) {
        $class = 'saturday'; // 土曜
      } elseif ($index === 6) {
        $class = 'sunday'; // 日曜
      }
      $html[] = '<th class="border ' . $class . '">' . $day . '</th>';
    }
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");

        $date = $day->everyDay();
        $carbonDate = Carbon::parse($date);
        $weekDay = $carbonDate->dayOfWeek; // 0:日曜, 6:土曜
        if ($startDay <= $date && $toDay >= $date) {
          $colorClass = '';
          if ($weekDay === 0) {
            $colorClass = 'sunday';
          } elseif ($weekDay === 6) {
            $colorClass = 'saturday';
          }
          $html[] = '<td class="past-day border ' . $colorClass . '">';
        } else {
          $html[] = '<td class="border ' . $day->getClassName() . '">';
        }

        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';
        if ($day->everyDay()) {
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="adjust-part-row">1部<input class="frame-input"name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '" disabled></p>';
            $html[] = '<p class="adjust-part-row">2部<input class="frame-input"name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '" disabled></p>';
            $html[] = '<p class="adjust-part-row">3部<input class="frame-input"  name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '" disabled></p>';
          } else {
            $html[] = '<p class="adjust-part-row">1部<input class="frame-input"  name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '"></p>';
            $html[] = '<p class="adjust-part-row">2部<input class="frame-input"  name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '"></p>';
            $html[] = '<p class="adjust-part-row">3部<input class="frame-input"  name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '"></p>';
          }
        }
        $html[] = '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '<div class="adjust-table-btn text-right mt-3">';
    $html[] = '<input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm(\'登録してよろしいですか？\')">';
    $html[] = '</div>';

    $html[] = '</div>';
    $html[] = '<form action="' . route('calendar.admin.update') . '" method="post" id="reserveSetting">' . csrf_field() . '</form>';
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
