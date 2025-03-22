<?php
namespace App\Searchs;

use App\Models\Users\User;

class AllUsers implements DisplayUsers
{
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects)
  {
    $updown = strtolower($updown);
    if (!in_array($updown, ['asc', 'desc'])) {
      $updown = 'asc';
    }

    $gender = is_null($gender) ? ['1', '2', '3'] : [$gender];
    $role = is_null($role) ? ['1', '2', '3', '4'] : [$role];

    $query = User::with('subjects')
      ->whereIn('sex', $gender)
      ->whereIn('role', $role);

    if (!is_null($subjects)) {
      $query->whereHas('subjects', function ($q) use ($subjects) {
        $q->whereIn('subjects.id', $subjects);
      });
    }

    return $query->orderBy('id', $updown)->get();
  }
}
