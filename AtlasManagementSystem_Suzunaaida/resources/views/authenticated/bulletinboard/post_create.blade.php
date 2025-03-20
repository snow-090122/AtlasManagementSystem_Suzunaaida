<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
      <!-- 投稿フォーム -->
      <form action="{{ route('post.create') }}" method="post" id="postCreate" onsubmit="checkForm(event)">
        @csrf
        <script>
          function checkForm(event) {
            let subCategoryId = document.querySelector('select[name="sub_category_id"]').value;
            console.log("送信される sub_category_id:", subCategoryId);
          }
        </script>

        <!-- カテゴリー -->
        <div class="">
          @error('sub_category_id')
        <p class="text-danger">{{ $message }}</p>
      @enderror
          <p class="mb-0">カテゴリー</p>
          <select class="w-100" name="sub_category_id" required>
            <option value="">選択してください</option>
            @foreach ($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}">
          @foreach ($main_category->subCategories as $sub_category)
        <option value="{{ intval($sub_category->id) }}" {{ old('sub_category_id') == $sub_category->id ? 'selected' : '' }}>
        {{ $sub_category->sub_category }}
        </option>
      @endforeach
        </optgroup>
      @endforeach
          </select>
        </div>


        <!-- タイトル -->
        <div class="mt-3">
          @error('post_title')
        <p class="text-danger">{{ $message }}</p>
      @enderror
          <p class="mb-0">タイトル</p>
          <input type="text" class="w-100" name="post_title" value="{{ old('post_title') }}">
        </div>

        <!-- 投稿内容 -->
        <div class="mt-3">
          @error('post_body')
        <p class="text-danger">{{ $message }}</p>
      @enderror
          <p class="mb-0">投稿内容</p>
          <textarea class="w-100" name="post_body">{{ old('post_body') }}</textarea>
        </div>

        <!-- 送信ボタン -->
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
      <p class="text-danger">{{ $message }}</p>
    @enderror
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0">
      </form>

      <!-- サブカテゴリー追加 -->
      <form action="{{ route('sub.category.create') }}" method="post">
        @csrf
        @error('sub_category')
      <p class="text-danger">{{ $message }}</p>
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
        <input type="text" class="w-100" name="sub_category" placeholder="サブカテゴリー名">

        @if($main_categories->isNotEmpty())
      <input type="submit" value="追加" class="w-100 btn btn-primary p-0">
    @else
    <p class="text-danger">メインカテゴリーを先に追加してください。</p>
  @endif
      </form>
      </div>
    </div>
  @endcan
  </div>
</x-sidebar>
