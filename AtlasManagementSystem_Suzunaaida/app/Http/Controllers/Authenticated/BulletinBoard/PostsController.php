<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\PostSubCategory;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\BulletinBoard\CommentRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with(['user', 'postComments', 'likes', 'subCategories'])
            ->withCount('likes', 'postComments')
            ->latest();

        if (!empty($request->keyword)) {
            // キーワードがサブカテゴリー名と完全一致するかチェック
            $subcategory = SubCategory::where('sub_category', $request->keyword)->first();

            if ($subcategory) {
                // 一致する場合、そのサブカテゴリーの投稿のみ取得
                $posts->whereHas('subCategories', function ($query) use ($subcategory) {
                    $query->where('sub_categories.id', $subcategory->id);
                });
            } else {
                // 一致しない場合、タイトル・投稿内容のあいまい検索
                $posts->where(function ($query) use ($request) {
                    $query->where('post_title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('post', 'like', '%' . $request->keyword . '%');
                });
            }
        }

        if ($request->category_word) {
            $sub_category = $request->category_word;
            $posts->whereHas('subCategories', function ($query) use ($sub_category) {
                $query->where('id', $sub_category);
            });
        }

        if ($request->like_posts) {
            $likes = Auth::user()->likePostId();
            $posts->whereIn('id', $likes);
        }

        if ($request->my_posts) {
            $posts->where('user_id', Auth::id());
        }

        $posts = $posts->get();
        $categories = MainCategory::all();

        $liked_posts = Auth::user() ? Auth::user()->likePostId() : [];

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'liked_posts'));
    }



    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $user = Auth::user();
        $post = Post::create([
            'user_id' => $user->id,
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);

        if ($request->has('sub_category_id')) {
            $subCategoryId = (int) $request->sub_category_id;
            $post->subCategories()->sync([$subCategoryId]); // 中間テーブルに登録
        }

        return redirect()->route('post.show')->with('success', '投稿が完了しました！');
    }

    public function postUpdate(Request $request, $id)
    {
        $request->validate([
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ], [
            'post_title.required' => 'タイトルは必須です。',
            'post_title.string' => 'タイトルは文字列で入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '投稿内容は必須です。',
            'post_body.string' => '投稿内容は文字列で入力してください。',
            'post_body.max' => '投稿内容は2000文字以内で入力してください。',
        ]);

        $post = Post::findOrFail($id);
        $post->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        return response()->json(['success' => true, 'message' => '更新成功']);
    }


    public function postDelete($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => '投稿が見つかりません',
                'redirect' => route('post.index')
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => '削除成功',
            'redirect' => route('post.index') // 一覧ページへ
        ], 200);
    }


    public function index()
    {
        return response()->view('authenticated.bulletinboard.posts', [
            'posts' => Post::latest()->get()
        ])->header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0")
            ->header("Pragma", "no-cache")
            ->header("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");
    }

    public function mainCategoryCreate(Request $request)
    {
        $request->validate([
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category'
        ], [
            'main_category_name.required' => 'メインカテゴリー名を入力してください。',
            'main_category_name.string' => 'メインカテゴリー名は文字列である必要があります。',
            'main_category_name.max' => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique' => 'このメインカテゴリー名はすでに存在しています。',
        ]);

        MainCategory::create(['main_category' => $request->main_category_name]);

        return redirect()->route('post.input')->with('success', 'メインカテゴリーを追加しました！');
    }

    public function subCategoryCreate(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category' => 'required|string|max:100|unique:sub_categories,sub_category'
        ], [
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
            'sub_category.required' => 'サブカテゴリー名を入力してください。',
            'sub_category.string' => 'サブカテゴリー名は文字列である必要があります。',
            'sub_category.max' => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category.unique' => 'このサブカテゴリー名はすでに存在しています。',
        ]);

        SubCategory::create([
            'sub_category' => $request->sub_category,
            'main_category_id' => $request->main_category_id,
        ]);

        return redirect()->route('post.input')->with('success', 'サブカテゴリーを追加しました！');
    }

    public function myBulletinBoard()
    {
        // ログインユーザーを取得
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        // 自分の投稿のみ取得
        $posts = Post::with(['user', 'postComments', 'likes'])
            ->withCount('likes', 'postComments')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // カテゴリー情報を取得
        $categories = MainCategory::all();
        $liked_posts = Auth::user() ? Auth::user()->likePostId() : [];

        // データ確認用
        if ($posts->isEmpty()) {
            return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'liked_posts'))
                ->with('message', 'あなたの投稿はありません');
        }

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'liked_posts'));
    }


    public function likeBulletinBoard()
    {
        $like_post_ids = Like::where('like_user_id', Auth::id())->pluck('like_post_id');

        $posts = Post::with('user')
            ->withCount('likes')
            ->whereIn('id', $like_post_ids)
            ->latest()
            ->get();

        return view('authenticated.bulletinboard.post_like', compact('posts'));
    }
    public function postLike(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        $user = Auth::user(); // ユーザー情報を取得
        $post_id = $request->post_id;

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ユーザーが認証されていません。'
            ], 401);
        }

        $existing_like = Like::where('like_user_id', $user->id)
            ->where('like_post_id', $post_id)
            ->exists();

        if (!$existing_like) {
            Like::create([
                'like_user_id' => $user->id,
                'like_post_id' => $post_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'like_count' => Like::where('like_post_id', $post_id)->count()
        ]);
    }

    public function postUnLike(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        $user = Auth::user();
        $post_id = $request->post_id;

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ユーザーが認証されていません。'
            ], 401);
        }

        Like::where('like_user_id', $user->id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json([
            'success' => true,
            'like_count' => max(0, Like::where('like_post_id', $post_id)->count())
        ]);
    }
    public function commentCreate(CommentRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors('ログインしていません。');
        }

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }
    public function showByCategory($sub_category_id)
    {
        // 指定されたサブカテゴリーに属する投稿を取得
        $posts = Post::whereHas('subCategories', function ($query) use ($sub_category_id) {
            $query->where('sub_categories.id', $sub_category_id);
        })->with(['user', 'postComments', 'likes'])->withCount('likes', 'postComments')->latest()->get();

        // カテゴリー情報を取得
        $subCategory = SubCategory::findOrFail($sub_category_id);
        $categories = MainCategory::all();
        $liked_posts = Auth::user() ? Auth::user()->likePostId() : [];

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'liked_posts', 'subCategory'));
    }

}
