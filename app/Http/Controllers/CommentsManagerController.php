<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Services\Slug;

class CommentsManagerController extends Controller
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
        if (Gate::denies('manage-comments')) {
            abort(403, 'Unauthorized action');
        }
        return view('comments.admin.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('manage-comments')) {
            abort(403, 'Unauthorized action');
        }

        $items = Comment::withTrashed()->latest()
            ->with([
                'post' => function ($query) {
                    $query->select(['id', 'post_title', 'post_slug']);
                },
                'user' => function ($query) {
                    $query->select(['id', 'name']);
                }
            ])
            ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));

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
     * This funciton is disabled for now
     *
     * @param App\Services\Slug $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Slug $slug, Request $request)
    {
        //if (Gate::denies('manage-comments')) {
        if (true) {
            abort(403, 'Unauthorized action');
        }

        $this->validate($request, [
            'comment_body' => 'required',
        ]);

        $comment = new Comment();
        $comment->comment_body = $request->get('comment_body');
        $comment->save();

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * This funciton is disabled for now
     *
     * @param App\Services\Slug $slug
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id    comment id
     * @return \Illuminate\Http\Response
     */
    public function update(Slug $slug, Request $request, $id)
    {
        // if (Gate::denies('manage-comments')) {
        if (true) {
            abort(403, 'Unauthorized action');
        }

        $comment = Comment::withTrashed()->find($id);

        $rules = [
            'comment_body' => 'required',
        ];
        $this->validate($request, $rules);

        $post->comment_body = $request->get('comment_body');
        $post->update();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id comment id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (Gate::denies('manage-comments')) {
            abort(403, 'Unauthorized action');
        }

        $comment = Comment::withTrashed()->find($id);
        $comment->trashed() ? $comment->restore() : $comment->delete();
        return response()->json(['done']);
    }
}
