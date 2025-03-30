<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

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

  function render()
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
        $dateStr = $day->everyDay();
        $currentDate = $dateStr ? Carbon::parse($dateStr) : null;

        $isCurrentMonth = $currentDate && $currentDate->format('m') == $this->carbon->format('m');
        $isPast = $currentDate && $currentDate->lte(Carbon::today());

        $tdClass = 'calendar-td';
        if (!$isCurrentMonth) {
          $tdClass .= ' bg-secondary';
        } elseif ($isPast) {
          $tdClass .= ' bg-secondary text-white';
        } else {
          $tdClass .= ' ' . $day->getClassName();
        }

        $html[] = '<td class="' . $tdClass . '">';
        $html[] = $day->render();

        if (!$isCurrentMonth) {
          // 空白マス：何も表示しない
          $html[] = '';
        } elseif (in_array($dateStr, $day->authReserveDay())) {
          $reservePart = $day->authReserveDate($dateStr)->first()->setting_part;
          $reserveLabel = 'リモ' . $reservePart . '部';

          if (!$isPast) {
            $html[] = '<button type="button" class="btn btn-danger p-0 w-75 open-cancel-modal"';
            $html[] = ' data-date="' . $dateStr . '"';
            $html[] = ' data-time="' . $reserveLabel . '"';
            $html[] = ' style="font-size:12px">' . $reserveLabel . '</button>';
          } else {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">' . $reserveLabel . '参加</p>';
          }


          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        } else {
          if ($isPast) {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            $html[] = $day->selectPart($dateStr);
          }
        }

        $html[] = $day->getDate();
        $html[] = '</td>';
      }

      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
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
