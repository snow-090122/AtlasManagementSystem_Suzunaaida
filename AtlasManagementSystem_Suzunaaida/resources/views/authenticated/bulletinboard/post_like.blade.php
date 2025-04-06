<x-sidebar>
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">いいねした投稿</p>
    @foreach ($posts as $post)
    <div class="post_area m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span>
    <span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.delete', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
      <p class="m-0">
      @if(in_array($post->id, $liked_posts))
      <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
  @else
    <i class="far fa-heart like_btn" post_id="{{ $post->id }}"></i>
@endif
      <span class="like_counts like_counts{{ $post->id }}">{{ $post->like_count }}</span>
      </p>
      </div>
    </div>
  @endforeach
  </div>
</x-sidebar>
