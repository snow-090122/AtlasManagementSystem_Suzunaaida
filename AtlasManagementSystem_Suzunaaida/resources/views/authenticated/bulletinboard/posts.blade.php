<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>

      @if(count($posts) > 0)
      @foreach($posts as $post)
      <div class="post_area w-75 m-auto p-4 mb-4 shadow-sm rounded">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p class="mt-2 mb-2 font-weight-bold text-primary">
      <a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a>
      </p>

      {{-- サブカテゴリーバッジ --}}
      @foreach($post->subCategories as $subCategory)
      <span class="category_box">
      {{ $subCategory->sub_category }}
      </span>
    @endforeach

      <div class="post_bottom_area d-flex justify-content-end align-items-center mt-3 gap-4">
      <div class="d-flex post_status">
      <div class="mr-5">
        <i class="fa fa-comment"></i>
        <span>{{ $post->post_comments_count }}</span>
      </div>
      <div>
        @if(Auth::user()->is_Like($post->id))
        <p class="m-0">
        <i class="fas fa-heart un_like_btn" data-post_id="{{ $post->id }}"></i>
        <span class="like_counts like_counts{{ $post->id }}">
        {{ max(0, $post->likes_count) }}
        </span>
        </p>
    @else
      <p class="m-0">
      <i class="fas fa-heart like_btn" data-post_id="{{ $post->id }}"></i>
      <span class="like_counts like_counts{{ $post->id }}">
      {{ max(0, $post->likes_count) }}
      </span>
      </p>
  @endif
      </div>
      </div>
      </div>
      </div>
    @endforeach
    @else
      <p class="w-75 m-auto text-center text-muted">現在、自分の投稿はありません。</p>
    @endif

    </div>

    <!-- 右側サイドバー -->
    <div class="other_area" style="width: 400px;">
      <div class="border m-4">

        {{-- 投稿ボタン --}}
        <div class="mb-3">
          <a href="{{ route('post.input') }}" class="form_btn blue_btn">投稿</a>
        </div>

        {{-- キーワード検索フォーム --}}
        <div class="search_group">
          <input type="text" name="keyword" placeholder="キーワードを検索" class="search_input_flat" form="postSearchRequest">
          <button type="submit" class="search_button_flat" form="postSearchRequest">検索</button>
        </div>


        {{-- いいね・自分の投稿 --}}
        <div class="d-flex gap-2 mb-3">
          <input type="submit" name="like_posts" value="いいねした投稿" class="like_post_btn" form="postSearchRequest">
          <button type="button" class="my_post_btn" onclick="window.location.href='{{ route('my.bulletin.board') }}'">自分の投稿</button>
        </div>

        <!-- メインカテゴリー一覧 -->
        <ul>
          @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}">
        <div class="category-header">
          <span>{{ $category->main_category }}</span>
          <i class="fas fa-chevron-down toggle-arrow" id="arrow{{ $category->id }}"></i>
        </div>

        <!-- サブカテゴリ -->
        @if($category->subCategories->isNotEmpty())
      <ul class="sub-category-list category_num{{ $category->id }}" style="display: none;">
        @foreach($category->subCategories as $sub_category)
      <li>
      <a href="{{ route('posts.byCategory', ['sub_category_id' => $sub_category->id]) }}">
      {{ $sub_category->sub_category }}
      </a>
      </li>
    @endforeach
      </ul>
    @endif
        </li>
      @endforeach
        </ul>
      </div>
    </div>

    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>
</x-sidebar>
