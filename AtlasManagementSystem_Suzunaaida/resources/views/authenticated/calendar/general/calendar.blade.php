<x-sidebar>
  <div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
      <div class="w-75 m-auto border" style="border-radius:5px;">

        <!-- カレンダータイトル -->
        <p class="text-center">{{ $calendar->getTitle() }}</p>

        <!-- カレンダー描画 -->
        <div class="">
          {!! $calendar->render() !!}

          <!-- キャンセル確認モーダル -->
          <div class="modal" id="cancelModal" tabindex="-1" style="display: none; position: fixed; z-index: 9999; top: 30%; left: 50%; transform: translate(-50%, -30%); background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
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
      </div>

      <!-- 予約ボタン -->
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
      </div>
    </div>
  </div>
</x-sidebar>
