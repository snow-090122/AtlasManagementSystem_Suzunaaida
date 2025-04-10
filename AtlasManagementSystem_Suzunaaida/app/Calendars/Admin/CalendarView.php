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
    $html[] = '<div class="calendar">';

    $html[] = '<div class="calendar-title">' . $this->getTitle() . '</div>';

    $html[] = '<table class="table-calendar">';

    $html[] = '<thead><tr>';
    $weekdays = ['月', '火', '水', '木', '金', '土', '日'];
    foreach ($weekdays as $index => $day) {
      $class = ($index === 5) ? 'saturday' : (($index === 6) ? 'sunday' : '');
      $html[] = '<th class="' . $class . '">' . $day . '</th>';
    }
    $html[] = '</tr></thead><tbody>';

    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr>';

      foreach ($week->getDays() as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $date = $day->everyDay();
        $toDay = Carbon::today()->format("Y-m-d");

        // 土曜・日曜の色付け
        $weekDay = Carbon::parse($date)->dayOfWeek;
        $colorClass = '';
        if ($weekDay === 6) {
          $colorClass = 'saturday';
        } elseif ($weekDay === 0) {
          $colorClass = 'sunday';
        }

        $isPast = $startDay <= $date && $toDay >= $date;
        $tdClass = $isPast ? 'past-day' : '';
        $tdClass .= ' ' . $colorClass;

        $html[] = '<td class="calendar-td ' . $tdClass . '" data-date="' . $date . '">';
        $html[] = '<div class="date-number">' . $day->render() . '</div>';

        $html[] = '<div class="parts-wrapper">';
        $html[] = $day->dayPartCounts($day->everyDay());
        $html[] = '</div>';

        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>'; // .calendarの閉じタグ

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
