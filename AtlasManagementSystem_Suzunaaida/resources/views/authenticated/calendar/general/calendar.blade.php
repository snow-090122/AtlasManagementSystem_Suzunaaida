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

    <!-- モーダルの背景 -->
    <div class="modal-overlay" id="cancelModalOverlay"></div>

    <!-- キャンセル確認モーダル -->
    <div class="modal" id="cancelModal">
      <div class="cancel-modal-content">
        <h5>予約キャンセル確認</h5>
        <p>予約日：<span id="modal-date"></span></p>
        <p>予約時間：<span id="modal-time"></span></p>
        <p>上記の予約をキャンセルしてよろしいですか？</p>
        <form id="cancel-form" method="POST" action="{{ route('deleteParts') }}">
          @csrf
          <input type="hidden" name="date" id="input-date">
          <input type="hidden" name="part" id="input-part">
          <div class="cancel-modal-buttons">
            <button type="submit" class="btn btn-danger">キャンセルする</button>
            <button type="button" class="btn btn-primary close-modal">閉じる</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</x-sidebar>
