<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Post;
use Redirect;
use Validator;
use App\Services\Slug;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // List is visible to logged in admin only
        if (!Auth::check()) {
            return redirect('/login');
        }

        if($filter = $request->get('filter')){
            $posts = Post::withTrashed()
                ->where('post_title', 'LIKE', sprintf('%%%s%%', trim($filter)))
                ->orWhere('post_body', 'LIKE', sprintf('%%%s%%', trim($filter)))
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'))
                ->appends(['filter' => $request->get('filter')]);
        }
        else {
            $posts = Post::withTrashed()
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));
        }

        $baseurl = '/posts';

        return view('posts.admin.list', ['posts' => $posts, 'baseurl' => $baseurl, 'filter' => $filter]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // redirect to form on main page
        return redirect('/#postForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param  App\Services\Slug        $slug
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Slug $slug)
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Check if user is logged
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!$post) {
            return '';
        }
        return view('posts.admin.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Post                $post
     * @param  App\Services\Slug        $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Slug $slug)
    {

        if (!Auth::check()) {
            abort(404);
        }

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 500);
        }

        $validator = Validator::make($request->all(), [
            'post_title' => 'required|max:255',
            'post_body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $success = true;
        $message = 'Record updated';
        $code = 200;
        try {
            $post->post_title = $request->get('post_title');
            $post->post_slug = $slug->createSlug($request->get('post_title'));
            $post->post_body = $request->get('post_body');
            $post->save();
        } catch (Exception $e) {
            $success = false;
            $message = ['database_error' => $e->getMessage()];
            $code = 500;
        }

        return response()->json([
                'success' => $success,
                'message' => $message
            ], $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Request $request)
    {

        if (!Auth::check()) {
            abort(404);
        }

        $success = true;
        $message = 'Record deleted';
        $code = 200;

        try {
            DB::beginTransaction();

            if (!$request->get('isSoft')) {
                $post->forceDelete();
            } else {
                $post->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            $success = false;
            $message =  $e->getMessage();
            $code = 500;
        }

        return response()->json([
                'success' => $success,
                'message' => $message
            ], $code);
    }

    /**
     * Unhide the specified resource from storage.
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function unhide(Request $request)
    {

        if (!Auth::check()) {
            abort(404);
        }

        if (!$post = Post::withTrashed()->find($request->get('id'))) {
            abort(404);
        }

        $success = true;
        $message = 'Record restored';
        $code = 200;

        try {
            if (!$request->get('fullDelete')) {
                $post->restore();
            } else {
                DB::beginTransaction();
                $post->forceDelete();
                $message = 'Record fully deleted';
                DB::commit();
            }
        } catch (Exception $e) {
            $success = false;
            $message =  $e->getMessage();
            $code = 500;
        }

        return response()->json([
                'success' => $success,
                'message' => $message
            ], $code);
    }

    /**
     * Approve or unapprove post
     * @param  Illuminate\Http\Request $request
     * @param  \App\Post $post
     */
     public function approve(Request $request, Post $post){

        if (!Auth::check()) {
            abort(404);
        }

        if (!$post = Post::find($request->get('id'))) {
            abort(404);
        }

        $success = true;
        $message = $request->get('flag') ? 'Record approved' : 'Record unapproved';
        $code = 200;

        try {

            $post->is_approved = $request->get('flag');
            $post->save();

        } catch (Exception $e) {
            $success = false;
            $message =  $e->getMessage();
            $code = 500;
        }

        return response()->json([
                'success' => $success,
                'message' => $message
            ], $code);
     }

}
