<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use \App\Post;

/**
 * Home page and actions:
 * - show single post with comments
 * - create new post
 * - create new comment
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
     * @param  App\Services\Slug        $slug
     * @return \Illuminate\Http\Response
     */
    public function createPost(Request $request, Slug $slug)
    {

        // @see https://laravel.com/docs/5.4/validation#introduction

        $user = Auth::user();

        $this->validate($request, [
            'post_title' => 'required|max:255',
            'post_body' => 'required',
        ]);

        $post = new Post();
        $post->user_id = $user->id;
        $post->post_title = $request->get('post_title');
        $post->post_slug = $slug->createSlug($request->get('post_title'));
        $post->post_body = $request->get('post_body');
        $post->created_at = new \DateTime();
        $post->save();

        return redirect('/');
    }
}
