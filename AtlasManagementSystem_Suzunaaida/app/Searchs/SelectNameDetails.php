<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers
{
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects)
  {
    $updown = strtolower($updown);
    if (!in_array($updown, ['asc', 'desc'])) {
      $updown = 'asc';
    }

    $gender = is_null($gender) ? ['1', '2', '3'] : [$gender];
    $role = is_null($role) ? ['1', '2', '3', '4'] : [$role];

    $users = User::with('subjects')
      ->where(function ($q) use ($keyword) {
        $q->where('over_name', 'like', '%' . $keyword . '%')
          ->orWhere('under_name', 'like', '%' . $keyword . '%')
          ->orWhere('over_name_kana', 'like', '%' . $keyword . '%')
          ->orWhere('under_name_kana', 'like', '%' . $keyword . '%');
      })
      ->whereIn('sex', $gender)
      ->whereIn('role', $role)
      ->whereHas('subjects', function ($q) use ($subjects) {
        $q->whereIn('subjects.id', $subjects);
      })
      ->orderBy('over_name_kana', $updown)
      ->get();

    return $users;
  }
}
