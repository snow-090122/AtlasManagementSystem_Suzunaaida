<x-sidebar>
  <div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-75 m-auto h-75">
      {{-- 日付と部を表示 --}}
      <p><span>{{ $date }}　{{ $part }}部</span></p>

      <div class="h-75 border p-3 overflow-auto">
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th class="w-25">ID</th>
              <th class="w-25">名前</th>
              <th class="w-25">場所</th>
            </tr>
          </thead>
          <tbody>
            {{-- データがある場合 --}}
            @forelse ($reserveSetting->users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->over_name }} {{ $user->under_name }}</td>
          <td>リモート</td>
        </tr>
      @empty
    <tr>
      <td colspan="3">予約者はいません</td>
    </tr>
  @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-sidebar>
