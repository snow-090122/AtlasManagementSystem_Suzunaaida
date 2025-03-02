<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments')->latest();

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
            $likes = Auth::user()->likePostId()->pluck('like_post_id');
            $posts->whereIn('id', $likes);
        }

        if ($request->my_posts) {
            $posts->where('user_id', Auth::id());
        }

        $posts = $posts->get();
        $categories = MainCategory::all();
        $like = new Like;
        $post_comment = new Post;

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::all();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $user = Auth::user();
        Post::create([
            'user_id' => $user->id,
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        return redirect()->route('post.show');
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

        $post->delete();
        return redirect()->route('post.show');
    }

    public function mainCategoryCreate(Request $request)
    {
        $request->validate(['main_category_name' => 'required|string|max:255']);

        MainCategory::create(['main_category' => $request->main_category_name]);

        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:500'
        ]);

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->with('user', 'postComments')->latest()->get();
        $like = new Like;

        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_ids = Like::where('like_user_id', Auth::id())->pluck('like_post_id');
        $posts = Post::with('user')->whereIn('id', $like_post_ids)->latest()->get();
        $like = new Like;

        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        $user_id = Auth::id();
        $post_id = $request->post_id;

        $existing_like = Like::where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->first();

        if (!$existing_like) {
            Like::create([
                'like_user_id' => $user_id,
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

        $user_id = Auth::id();
        $post_id = $request->post_id;

        Like::where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json([
            'success' => true,
            'like_count' => Like::where('like_post_id', $post_id)->count()
        ]);
    }
}
