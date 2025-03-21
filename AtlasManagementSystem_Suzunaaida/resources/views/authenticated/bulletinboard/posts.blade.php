<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>

      @if(count($posts) > 0)
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
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
    <div class="other_area border w-25">
      <div class="border m-4">
        <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
        <button type="button" class="category_btn" onclick="window.location.href='{{ route('my.bulletin.board') }}'">自分の投稿</button>

        <!-- メインカテゴリー一覧 -->
        <ul>
          @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}">
        <span>{{ $category->main_category }}</span>
        <!-- サブカテゴリー一覧 -->
        @if($category->subCategories->isNotEmpty())
      <ul class="sub-category-list">
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
