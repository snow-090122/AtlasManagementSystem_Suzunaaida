<x-guest-layout>
  <div class="login-page">
    {{-- ロゴ --}}
    <div class="logo-box">
      <img src="{{ asset('image/atlas-black.png') }}" alt="Atlas ロゴ" class="login-logo">
    </div>

    {{-- ログインフォーム --}}
    <form action="{{ route('login') }}" method="POST" class="login-form-box">
      @csrf

      {{-- メールアドレス --}}
      <div class="mb-4">
        <label class="d-block m-0" style="font-size:13px;">メールアドレス</label>
        <input type="text" name="mail_address" class="login-input">
      </div>

      {{-- パスワード --}}
      <div class="mb-4">
        <label class="d-block m-0" style="font-size:13px;">パスワード</label>
        <input type="password" name="password" class="login-input">
      </div>

      {{-- ログインボタン --}}
      <div class="text-right m-3">
        <input type="submit" class="btn btn-primary" value="ログイン">
      </div>


      {{-- 新規登録リンク --}}
      <div class="text-center">
        <a href="{{ route('registerView') }}" class="text-primary" style="font-size: 14px;">新規登録はこちら</a>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}"></script>
</x-guest-layout>
