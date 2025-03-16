<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
use App\Http\Requests\BulletinBoard\CommentRequest;



class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with(['user', 'postComments', 'likes'])
            ->withCount('likes', 'postComments')
            ->latest();

        if (!empty($request->keyword)) {
            $posts->where(function ($query) use ($request) {
                $query->where('post_title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('post', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->category_word) {
            $sub_category = $request->category_word;
            $posts->whereHas('subCategory', function ($query) use ($sub_category) {
                $query->where('id', $sub_category);
            });
        }

        if ($request->like_posts) {
            $likes = Auth::user()->likes()->pluck('like_post_id')->toArray();
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


    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }
    public function postInput()
    {
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }


    public function postCreate(PostFormRequest $request)
    {
        // 送信されたデータを確認
        dd($request->all());

        $user = Auth::user();

        // 投稿を作成
        $post = Post::create([
            'user_id' => $user->id,
            'sub_category_id' => $request->sub_category_id,
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        return redirect()->route('post.show')->with('success', '投稿が作成されました！');
    }

    public function postEdit(PostFormRequest $request)
    {
        $post = Post::findOrFail($request->post_id);

        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => '他のユーザーの投稿は編集できません。'
            ], 403);
        }

        $post->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        return response()->json([
            'success' => true,
            'message' => '投稿が更新されました。',
            'updated_title' => $post->post_title,
            'updated_body' => $post->post
        ]);
    }


    public function postDelete($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return redirect()->route('post.show')->withErrors('他のユーザーの投稿は削除できません。');
        }

        // サブカテゴリーの関連を削除
        \DB::table('post_sub_categories')->where('post_id', $post->id)->delete();

        $post->delete();
        return redirect()->route('post.show')->with('success', '投稿が削除されました！');
    }


    public function mainCategoryCreate(Request $request)
    {
        try {
            MainCategory::create(['main_category' => $request->main_category_name]);
            return redirect()->route('post.input')->with('success', 'メインカテゴリーを追加しました！');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('このメインカテゴリーはすでに登録されています。');
        }
    }

    public function subCategoryCreate(Request $request)
    {
        //     // デバッグ：リクエストデータの確認
        //     dd($request->all());

        // サブカテゴリー専用のバリデーション
        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category' => 'required|string|max:100|unique:sub_categories,sub_category',
        ], [
            'sub_category.required' => 'サブカテゴリーを必ず入力してください。',
            'sub_category.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category.max' => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category.unique' => 'このサブカテゴリー名はすでに登録されています。',
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択されたメインカテゴリーが無効です。',
        ]);

        try {
            SubCategory::create([
                'sub_category' => $request->sub_category,
                'main_category_id' => $request->main_category_id,
            ]);

            return redirect()->route('post.input')->with('success', 'サブカテゴリーを追加しました！');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors('サブカテゴリーの追加中にエラーが発生しました: ' . $e->getMessage());
        }
    }


    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->with('user', 'postComments')->latest()->get();
        $like = new Like;

        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function postLike(Request $request)
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

        \DB::transaction(function () use ($user, $post_id) {
            $existing_like = Like::where('like_user_id', $user->id)
                ->where('like_post_id', $post_id)
                ->exists();

            if (!$existing_like) {
                Like::create([
                    'like_user_id' => $user->id,
                    'like_post_id' => $post_id,
                ]);
            }
        });

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

}
