@extends('..layouts.app')

@section('content')

<div class="container">

    <div class="searchbox">
        <div class="form-group">
            <input type="text" class="form-control" data-url="{{ $baseurl }}" placeholder="Search" id="search-filter" value="{{ $filter }}">
        </div>
    </div>

    <h1>Edit Comments</h1>

    <div class="comments">
        <div class="container">
            @foreach ($comments as $comment)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-right">Latest changes:
                          <strong> {{ $comment->updated_at ? $comment->updated_at->format('F d, Y H:i') : $comment->created_at->format('F d, Y H:i') }} </strong>
                        </div>
                        <h4>Post: <a href="/post/{{ $comment->post->post_slug }}" target="_blank" title="See the full posting with comments">{{ $comment->post->post_title }}</a></h4>
                        <p>by <em>{{ $comment->user->name }}</em>, <a href="mailto:{{ $comment->user->email }}">{{ $comment->user->email }}</a>:</p>
                        @if ($comment->trashed())
                        <p>
                            <span class="label label-warning">Hidden</span>
                        </p>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div class="pull-right">
                           <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions
                              <span class="caret"></span></button>
                              <ul class="dropdown-menu">
                                    @if (!$comment->trashed())
                                        <li><a href="#" class="admin-comment-edit" data-id="{{ $comment->id }}" data-pageid="{{ $comments->currentPage() }}">Edit</a></li>
                                        <li><a href="#" class="admin-comment-hide" data-id="{{ $comment->id }}" data-pageid="{{ $comments->currentPage() }}">Hide</a></li>
                                        <li><a href="#" class="admin-comment-delete" data-id="{{ $comment->id }}" data-pageid="{{ $comments->currentPage() }}">Delete</a></li>
                                    @else
                                        <li><a href="#" class="admin-comment-unhide" data-id="{{ $comment->id }}" data-pageid="{{ $comments->currentPage() }}">Unhide</a></li>
                                        <li><a href="#" class="admin-comment-deletehidden" data-id="{{ $comment->id }}" data-pageid="{{ $comments->currentPage() }}">Delete</a></li>
                                    @endif
                              </ul>
                            </div>
                        </div>
                        <p>
                            {{ nl2br($comment->comment_body) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

      <ul class="pagination">
          {{ $comments->links() }}
      </ul>

    </div>
</div>

<div id="admin-comment-edit-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Comment</h4>
            </div>
            <div class="modal-body">
                <div class="centered">
                    <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-ok">OK</button>
            </div>
        </div>
    </div>
</div>

@endsection
