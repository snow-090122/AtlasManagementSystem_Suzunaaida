<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectIdDetails implements DisplayUsers
{
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects)
  {
    $updown = strtolower($updown);
    if (!in_array($updown, ['asc', 'desc'])) {
      $updown = 'asc';
    }

    $keyword = is_null($keyword) ? User::pluck('id')->toArray() : [$keyword];
    $gender = is_null($gender) ? ['1', '2', '3'] : [$gender];
    $role = is_null($role) ? ['1', '2', '3', '4'] : [$role];

    $users = User::with('subjects')
      ->whereIn('id', $keyword)
      ->whereIn('sex', $gender)
      ->whereIn('role', $role)
      ->whereHas('subjects', function ($q) use ($subjects) {
        $q->whereIn('subjects.id', $subjects);
      })
      ->orderBy('id', $updown)
      ->get();

    return $users;
  }
}
