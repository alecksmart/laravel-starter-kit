<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Post;
use Redirect;
use Validator;
use App\Services\Slug;

class CommentsController extends Controller
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

        if ($filter = $request->get('filter')) {
            $comments = Comment::withTrashed()
                ->where('comment_body', 'LIKE', sprintf('%%%s%%', trim($filter)))
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'))
                ->appends(['filter' => $request->get('filter')])
            ;
        } else {
            $comments = Comment::withTrashed()
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));
        }

        $baseurl = '/comments';

        return view('comments.admin.list', ['comments' => $comments, 'baseurl' => $baseurl, 'filter' => $filter]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $post = Post::find($request->get('post_id'));
        $user = Auth::user();

        if (!$post) {
            return Redirect::back()->withErrors(['Post not found!']);
        }

        $this->validate($request, [
            'post_id' => 'required',
            'comment_body' => 'required',
        ]);

        $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->post_id = $post->id;
            $comment->comment_body = $request->get('comment_body');
            $comment->created_at = new \DateTime();
        $comment->save();

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        // Check if user is logged
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!$comment) {
            return '';
        }
        return view('comments.admin.edit', ['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Comment  $comment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, Request $request)
    {
        if (!Auth::check()) {
            abort(404);
        }

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 500);
        }

        $validator = Validator::make($request->all(), [
            'comment_body' => 'required',
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
            $comment->comment_body = $request->get('comment_body');
            $comment->save();
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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, Request $request)
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
                $comment->forceDelete();
            } else {
                $comment->delete();
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
     * Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function unhide(Request $request)
    {

        if (!Auth::check()) {
            abort(404);
        }

        if (!$comment = Comment::withTrashed()->find($request->get('id'))) {
            abort(404);
        }

        $success = true;
        $message = 'Record restored';
        $code = 200;

        try {
            if (!$request->get('fullDelete')) {
                $comment->restore();
            } else {
                DB::beginTransaction();
                $comment->forceDelete();
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
}
