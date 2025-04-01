<x-sidebar>
  <div class="vh-100 d-flex">
    <div class="w-50 mt-5">
      <div class="m-3 detail_container rounded">
        <div class="p-3">
          <div class="detail_inner_head">
            <div></div>
            <div>
              @if($post->user_id == Auth::user()->id)
          <button type="button" class="edit-modal-open btn btn-primary" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">
          編集
          </button>
          <button class="js-delete-btn btn btn-danger" data-post-id="{{ $post->id }}">
          削除
          </button>
        @endif
            </div>
          </div>

          <div class="contributor d-flex">
            <p>
              <span>{{ $post->user->over_name }}</span>
              <span>{{ $post->user->under_name }}</span> さん
            </p>
            <span class="ml-5">{{ $post->created_at }}</span>
          </div>
          <div class="detsail_post_title">{{ $post->post_title }}</div>
          <div class="mt-3 detsail_post">{{ $post->post }}</div>
        </div>

        <div class="p-3">
          <div class="comment_container">
            <span>コメント</span>
            @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
          <span>{{ $comment->user->over_name }}</span>
          <span>{{ $comment->user->under_name }}</span> さん
          </p>
            <p>{{ $comment->comment }}</p>
          </div>
      @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="w-50 p-3">
      <div class="comment_container border m-5">
        <div class="comment_area p-3">
          @if ($errors->has('comment'))
        <ul class="text-danger">
        @foreach ($errors->get('comment') as $message)
      <li>{{ $message }}</li>
    @endforeach
        </ul>
      @endif
          <p class="m-0">コメントする</p>
          <form action="{{ route('comment.create') }}" method="post" id="commentRequest">
            {{ csrf_field() }}
            <textarea class="w-100" name="comment"></textarea>
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <div class=" d-flex justify-content-end mt-2">
              <input type="submit" class="btn btn-primary" value="投稿">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- CSRFトークンをmetaタグに含める -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- 編集モーダル -->
  <div class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form id="edit-post-form">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="post_id" id="edit-post-id">

        <!-- タイトル入力 -->
        <div class="modal-inner-title w-50 m-auto">
          <ul class="text-danger mb-1" id="error-post-title" style="font-size: 14px;"></ul>
          <input type="text" name="post_title" id="edit-post-title" placeholder="タイトル" class="w-100">
        </div>

        <!-- 投稿内容入力 -->
        <div class="modal-inner-body w-50 m-auto">
          <ul class="text-danger mb-1" id="error-post-body" style="font-size: 14px;"></ul>
          <textarea name="post_body" id="edit-post-body" placeholder="投稿内容" class="w-100"></textarea>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="{{ $post->id }}">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
    </div>
    {{ csrf_field() }}
    </form>
  </div>
  </div>

  <!-- 削除モーダル -->
  <div class="modal js-delete-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form id="delete-form" action="" method="POST" class="delete-form">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <div class="w-100">
          <div class="modal-inner-title w-50 m-auto text-center">
            <p>本当にこの投稿を削除しますか？</p>
          </div>
          <div class="w-50 m-auto edit-modal-btn d-flex justify-content-around">
            <a class="js-modal-close btn btn-secondary" href="">キャンセル</a>
            <input type="hidden" class="delete-modal-hidden" name="post_id" value="">
            <input type="submit" class="btn btn-danger" value="削除">
          </div>
        </div>
      </form>
    </div>
  </div>

</x-sidebar>
