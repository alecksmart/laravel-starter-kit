<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Post;
use App\Comment;
use App\Services\Slug;
use Illuminate\Support\Facades\Gate;
use Redirect;

/**
 * Home page and actions:
 * - show single post with comments
 * - create new post
 * - create new comment
 *
 * Currently all posts are approved by default
 *
 */
class BlogController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // The default view retrieves and shows paginated posts
        $posts = Post::where('is_approved', 1)
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));

        return view('blog', ['posts' => $posts]);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show($post_slug)
    {
        if (!$post = Post::where('post_slug', $post_slug)->firstOrFail()) {
            abort(404);
        }
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param App\Services\Slug $slug
     * @return \Illuminate\Http\Response
     */
    public function createPost(Request $request, Slug $slug)
    {
        if (Gate::denies('post-create')) {
            abort(403, 'Unauthorized action');
        }

        $this->validate($request, [
            'post_title' => 'required|max:255',
            'post_body' => 'required',
        ]);

        $post = new Post();
        $post->user_id     = $request->user()->id;
        $post->post_title  = $request->get('post_title');
        $post->post_slug   = $slug->createSlug($request->get('post_title'));
        $post->post_body   = $request->get('post_body');
        $post->is_approved = true;
        $post->created_at  = new \DateTime();
        $post->save();

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createComment(Request $request)
    {
        if (Gate::denies('comment-create')) {
            abort(403, 'Unauthorized action');
        }

        $post = Post::find($request->get('post_id'));

        if (!$post) {
            return Redirect::back()->withErrors(['Post not found!']);
        }

        $this->validate($request, [
            'post_id' => 'required',
            'comment_body' => 'required',
        ]);

        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->post_id = $post->id;
        $comment->comment_body = $request->get('comment_body');
        $comment->created_at = new \DateTime();
        $comment->save();

        return Redirect::back();
    }
}
