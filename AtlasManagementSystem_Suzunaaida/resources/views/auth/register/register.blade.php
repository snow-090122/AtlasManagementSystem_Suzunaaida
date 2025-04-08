<x-guest-layout>
  <form action="{{ route('registerPost') }}" method="POST">
    @csrf
    <div class="register-page">
      <div class="register-form-box">
        {{-- 姓と名 --}}
        <div class="form-group-row">
          <div class="form-group">
            @error('over_name')
        <div class="error-message">{{ $message }}</div>
      @enderror
            <label for="over_name" class="form-label">姓</label>
            <div class="form-input">
              <input type="text" name="over_name" class="input-field" value="{{ old('over_name') }}">
            </div>
          </div>

          <div class="form-group">
            @error('under_name')
        <div class="error-message">{{ $message }}</div>
      @enderror
            <label for="under_name" class="form-label">名</label>
            <div class="form-input">
              <input type="text" name="under_name" class="input-field" value="{{ old('under_name') }}">
            </div>
          </div>
        </div>

        {{-- カナ --}}
        <div class="form-group-row">
          <div class="form-group">
            @error('over_name_kana')
        <div class="error-message">{{ $message }}</div>
      @enderror
            <label class="form-label">セイ</label>
            <div class="form-input">
              <input type="text" name="over_name_kana" class="input-field" value="{{ old('over_name_kana') }}">
            </div>
          </div>

          <div class="form-group">
            @error('under_name_kana')
        <div class="error-message">{{ $message }}</div>
      @enderror
            <label class="form-label">メイ</label>
            <div class="form-input">
              <input type="text" name="under_name_kana" class="input-field" value="{{ old('under_name_kana') }}">
            </div>
          </div>
        </div>

        {{-- メールアドレス --}}
        <div class="form-group">
          @error('mail_address')
        <div class="error-message">{{ $message }}</div>
      @enderror
          <label class="form-label">メールアドレス</label>
          <div class="form-input">
            <input type="email" name="mail_address" class="input-field" value="{{ old('mail_address') }}">
          </div>
        </div>

        {{-- 性別 --}}
        <div class="form-group">
          @error('sex')<div class="error-message">{{ $message }}</div>
      @enderror
          <div class="radio-group2">
            <label><input type="radio" name="sex" value="1" {{ old('sex') == 1 ? 'checked' : '' }}> 男性</label>
            <label><input type="radio" name="sex" value="2" {{ old('sex') == 2 ? 'checked' : '' }}> 女性</label>
            <label><input type="radio" name="sex" value="3" {{ old('sex') == 3 ? 'checked' : '' }}> その他</label>
          </div>
        </div>

        {{-- 生年月日 --}}
        <div class="form-group">
          @if ($errors->has('birth_date'))
        <div class="error-message">{{ $errors->first('birth_date') }}</div>
      @endif
          <label class="form-label">生年月日</label>
          <div>
            <select name="old_year" class="form-select">
              <option value="" disabled selected></option>
              @for($y = 1985; $y <= 2010; $y++)
          <option value="{{ $y }}" {{ old('old_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
            </select> 年
            <select name="old_month" class="form-select">
              <option value="" disabled selected></option>
              @for($m = 1; $m <= 12; $m++)
          <option value="{{ sprintf('%02d', $m) }}" {{ old('old_month') == sprintf('%02d', $m) ? 'selected' : '' }}>{{ $m }}</option>
        @endfor
            </select> 月
            <select name="old_day" class="form-select">
              <option value="" disabled selected></option>
              @for($d = 1; $d <= 31; $d++)
          <option value="{{ sprintf('%02d', $d) }}" {{ old('old_day') == sprintf('%02d', $d) ? 'selected' : '' }}>{{ $d }}</option>
        @endfor
            </select> 日
          </div>
        </div>

        {{-- 役職 --}}
        <div class="form-group">
          @error('role')<div class="error-message">{{ $message }}</div>@enderror
          <label class="form-label">役職</label>
          <div class="radio-group">
            <label><input type="radio" name="role" class="admin_role" value="1"> 教師(国語)</label>
            <label><input type="radio" name="role" class="admin_role" value="2"> 教師(数学)</label>
            <label><input type="radio" name="role" class="admin_role" value="3"> 教師(英語)</label>
            <label><input type="radio" name="role" class="other_role" value="4"> 生徒</label>
          </div>
        </div>

        {{-- 選択科目 --}}
        <div class="select_teacher d-none">
          <label class="form-label d-block m-0">選択科目</label>
          @foreach($subjects as $subject)
        <div class="">
        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}">
        <label>{{ $subject->subject }}</label>
        </div>
      @endforeach
        </div>


        {{-- パスワード --}}
        <div class="form-group">
          @error('password')
        <div class="error-message">{{ $message }}</div>
      @enderror
          <label class="form-label">パスワード</label>
          <div class="form-input">
            <input type="password" name="password" class="input-field">
          </div>
        </div>

        {{-- 確認用パスワード --}}
        <div class="form-group">
          @error('password_confirmation')
        <div class="error-message">{{ $message }}</div>
      @enderror
          <label class="form-label">確認用パスワード</label>
          <div class="form-input">
            <input type="password" name="password_confirmation" class="input-field">
          </div>
        </div>

        <div class="mt-5 text-right">
          <input type="submit" class="btn btn-primary register_btn" value="新規登録" onclick="return confirm('登録してよろしいですか？')">
        </div>
        <div class="text-center">
          <a href="{{ route('login') }}">ログイン</a>
        </div>
      </div>
      {{ csrf_field() }}
    </div>
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</x-guest-layout>
