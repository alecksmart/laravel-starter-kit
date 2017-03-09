<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Services\Slug;

class PostsManagerController extends Controller
{
    /**
     * All all actions require a user to be logged in...
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing placeholder of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        if (Gate::denies('manage-posts')) {
            abort(403, 'Unauthorized action');
        }
        return view('posts.admin.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('manage-posts')) {
            abort(403, 'Unauthorized action');
        }

        $items = Post::withTrashed()->latest()->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));

        $response = [
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'from'         => $items->firstItem(),
                'to'           => $items->lastItem()
            ],
            'data' => $items
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Services\Slug $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Slug $slug, Request $request)
    {
        if (Gate::denies('manage-posts')) {
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

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Services\Slug $slug
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id    post id
     * @return \Illuminate\Http\Response
     */
    public function update(Slug $slug, Request $request, $id)
    {
        if (Gate::denies('manage-users-update')) {
            abort(403, 'Unauthorized action');
        }

        $post = Post::withTrashed()->find($id);

        $rules = [
            'post_title' => 'required|max:255',
            'post_body' => 'required',
        ];
        $this->validate($request, $rules);

        $post->post_title  = $request->get('post_title');
        $post->post_slug   = $slug->createSlug($request->get('post_title'));
        $post->post_body   = $request->get('post_body');
        $post->update();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id post id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (Gate::denies('manage-users-delete')) {
            abort(403, 'Unauthorized action');
        }

        $post = Post::withTrashed()->find($id);
        $post->trashed() ? $post->restore() : $post->delete();
        return response()->json(['done']);
    }
}
