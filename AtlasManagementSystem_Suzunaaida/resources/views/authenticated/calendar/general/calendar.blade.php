<x-sidebar>
  <div class="container" style="max-width: 1000px; background: transparent; box-shadow: none;">

    <!-- カレンダー全体の白枠 -->
    <div class="calendar text-center">

      <!-- カレンダータイトル -->
      <p class="calendar-title">{{ $calendar->getTitle() }}</p>

      <!-- カレンダー本体描画 -->
      {!! $calendar->render() !!}

      <!-- 予約ボタン -->
      <div class="text-right mt-3">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
      </div>

    </div>

    <!-- キャンセル確認モーダル（これは枠の外でOK） -->
    <div class="modal" id="cancelModal" style="display:none; position:fixed; z-index:9999; top:30%; left:50%; transform:translate(-50%, -30%); background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2);">
      <div class="modal-content">
        <h5>予約キャンセル確認</h5>
        <p>予約日：<span id="modal-date"></span></p>
        <p>予約時間：<span id="modal-time"></span></p>
        <p>上記の予約をキャンセルしてよろしいですか？</p>
        <form id="cancel-form" method="POST" action="{{ route('deleteParts') }}">
          @csrf
          <input type="hidden" name="date" id="input-date">
          <input type="hidden" name="part" id="input-part">
          <button type="submit" class="btn btn-danger">キャンセルする</button>
          <button type="button" class="btn btn-secondary close-modal">閉じる</button>
        </form>
      </div>
    </div>

  </div>
</x-sidebar>
