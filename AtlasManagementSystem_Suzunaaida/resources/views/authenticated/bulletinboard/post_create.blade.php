<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
      <!-- 投稿フォーム -->
      <form action="{{ route('post.create') }}" method="post" id="postCreate">
        @csrf

        <div class="">
          <p class="mb-0">カテゴリー</p>
          <select class="w-100" name="post_category_id">
            @if(isset($main_categories) && $main_categories->isNotEmpty())
        @foreach($main_categories as $main_category)

      <option value="main_{{ $main_category->id }}">{{ $main_category->main_category }}</option>
      <optgroup label="{{ $main_category->main_category }}">
        @if($main_category->subCategories->isNotEmpty())
      @foreach($main_category->subCategories as $sub_category)
      <option value="{{ $sub_category->id }}">{{ $sub_category->sub_category }}</option>
    @endforeach
    @else
    <option value="" disabled>サブカテゴリーなし</option>
  @endif
      </optgroup>
    @endforeach
      @else
    <option value="" disabled>カテゴリーがありません</option>
  @endif
          </select>
        </div>

        <div class="mt-3">
          @error('post_title')
        <span class="error_message">{{ $message }}</span>
      @enderror
          <p class="mb-0">タイトル</p>
          <input type="text" class="w-100" name="post_title" value="{{ old('post_title') }}">
        </div>

        <div class="mt-3">
          @error('post_body')
        <span class="error_message">{{ $message }}</span>
      @enderror
          <p class="mb-0">投稿内容</p>
          <textarea class="w-100" name="post_body">{{ old('post_body') }}</textarea>
        </div>

        <div class="mt-3 text-right">
          <input type="submit" class="btn btn-primary" value="投稿">
        </div>
      </form>
    </div>

    @can('admin')
    <div class="w-25 ml-auto mr-auto">
      <div class="category_area mt-5 p-5">
      <!-- メインカテゴリー追加 -->
      <form action="{{ route('main.category.create') }}" method="post">
        @csrf
        @error('main_category_name')
      <span class="error_message">{{ $message }}</span>
    @enderror
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0">
      </form>

      <!-- サブカテゴリー追加 -->
      <form action="{{ route('sub.category.create') }}" method="post">
        @csrf
        @error('sub_category_name')
      <span class="error_message">{{ $message }}</span>
    @enderror
        <p class="m-0">サブカテゴリー</p>
        <select class="w-100" name="main_category_id">
        @if(isset($main_categories) && $main_categories->isNotEmpty())
      @foreach($main_categories as $main_category)
      <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
    @endforeach
    @else
    <option value="" disabled>カテゴリーがありません</option>
  @endif
        </select>
        <input type="text" class="w-100" name="sub_category_name">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0">
      </form>

      </div>
    </div>
  @endcan
  </div>
</x-sidebar>
