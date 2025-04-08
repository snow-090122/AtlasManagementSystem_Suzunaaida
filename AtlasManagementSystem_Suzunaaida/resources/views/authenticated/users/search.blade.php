<x-sidebar>
  <div class="search_content w-100 d-flex">
    <div class="reserve_users_area">
      @foreach($users as $user)
      <div class="border one_person">
      <div>
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
        <span>{{ $user->over_name }}</span>
        <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
      <span>性別 : </span><span>男</span>
    @elseif($user->sex == 2)
    <span>性別 : </span><span>女</span>
  @else
  <span>性別 : </span><span>その他</span>
@endif
      </div>
      <div>
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
      <span>権限 : </span><span>教師(国語)</span>
    @elseif($user->role == 2)
    <span>権限 : </span><span>教師(数学)</span>
  @elseif($user->role == 3)
  <span>権限 : </span><span>講師(英語)</span>
@else
  <span>権限 : </span><span>生徒</span>
@endif
      </div>
      <div>
        @if($user->role == 4)
      <span>選択科目 :</span>
      @if ($user->subjects->isNotEmpty())
      @foreach ($user->subjects as $subject)
      <span>{{ $subject->subject }}</span>
    @endforeach
    @else
      <span>なし</span>
    @endif
    @endif
      </div>
      </div>
    @endforeach
    </div>
    <div class="search_area w-25 ml-auto">
      <div class="">
        <p class="search_title">検索</p>
        <div>
          <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
        </div>
        <div class="user-search-group">
          <label class="user-search-label">カテゴリ</label>
          <select form="userSearchRequest" name="category" class="user-search-select">
            <option value="name">名前</option>
            <option value="id">社員ID</option>
          </select>
        </div>

        <div class="user-search-group">
          <label class="user-search-label">並び替え</label>
          <select name="updown" form="userSearchRequest" class="user-search-select">
            <option value="ASC">昇順</option>
            <option value="DESC">降順</option>
          </select>
        </div>
        <div class="">
          <p class="m-0 search_conditions">
            <span>検索条件の追加</span>
            <span class="arrow">▼</span>
          </p>

          <div class="search_conditions_inner">
            <div class="user-search-group">
              <label class="user-search-label">性別</label>
              <div class="user-search-radio-row">
                <label><input type="radio" name="sex" value="1" form="userSearchRequest"> 男</label>
                <label><input type="radio" name="sex" value="2" form="userSearchRequest"> 女</label>
                <label><input type="radio" name="sex" value="3" form="userSearchRequest"> その他</label>
              </div>
            </div>

            <div class="user-search-group">
              <label class="user-search-label">権限</label>
              <select name="role" form="userSearchRequest" class="engineer">
                <option selected disabled>----</option>
                <option value="1">教師(国語)</option>
                <option value="2">教師(数学)</option>
                <option value="3">教師(英語)</option>
                <option value="4">生徒</option>
              </select>
            </div>

            <div class="user-search-group">
              <label class="user-search-label">選択科目</label>
              <div class="user-search-subjects-checks">
                @foreach ($subjects as $subject)
          <label>
            {{ $subject->subject }}
            <input type="checkbox" name="subject[]" value="{{ $subject->id }}" form="userSearchRequest">
          </label>
        @endforeach
              </div>
            </div>
          </div>

          <div class="user-search-buttons">
            <input type="submit" name="search_btn" value="検索" form="userSearchRequest" class="search_btn">
            <input type="reset" value="リセット" form="userSearchRequest" class="reset_btn">
          </div>
        </div>
        <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
      </div>
    </div>
</x-sidebar>
