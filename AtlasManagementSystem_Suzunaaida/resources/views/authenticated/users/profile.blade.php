<x-sidebar>
  <div class="vh-100 border">
    <p class="profile-title mb-3 font-weight-bold">
          {{ $user->over_name }}{{ $user->under_name }}さんのプロフィール
        </p>
    <div class="top_area w-75 m-auto pt-5">
      <div class="user_status p-3">
        <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
        <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
        <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
        <p>生年月日 : <span>{{ $user->birth_day }}</span></p>
        <div>選択科目 :
          @foreach($user->subjects as $subject)
        <span>{{ $subject->subject }}</span>
      @endforeach
        </div>
        <div class="">
          @can('admin')
        <div class="subject_edit_box mt-2">
        <span class="subject_edit_btn">
          選択科目の登録 <span class="arrow">▼</span>
        </span>

        <div class="subject_inner mt-2">
          <form action="{{ route('user.edit') }}" method="post" class="subject_form d-flex align-items-center gap-3 flex-wrap">
          <div class="subject_checkboxes d-flex gap-3">
            @foreach($subject_lists as $subject_list)
        <label>
        {{ $subject_list->subject }}<input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
        </label>
      @endforeach
          </div>
          <input type="submit" value="登録" class="btn btn-primary">
          <input type="hidden" name="user_id" value="{{ $user->id }}">
          {{ csrf_field() }}
          </form>
        </div>
        </div>
      @endcan

        </div>
      </div>
    </div>
  </div>

</x-sidebar>
