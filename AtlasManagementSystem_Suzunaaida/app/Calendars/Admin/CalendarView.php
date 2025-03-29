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
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table table-bordered m-auto" style="table-layout: fixed; width: 100%;">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $weekdays = ['月', '火', '水', '木', '金', '土', '日'];
    foreach ($weekdays as $day) {
      $html[] = '<th class="border" style="width: 14.28%;">' . $day . '</th>';
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

        if ($startDay <= $date && $toDay >= $date) {
          $html[] = '<td class="past-day border" data-date="' . $date . '">';
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
    $html[] = '</div>';

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
