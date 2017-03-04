@extends('..layouts.app')

@section('content')

<div class="container">

    <div class="searchbox">
        <div class="form-group">
            <input type="text" class="form-control" data-url="{{ $baseurl }}" placeholder="Search" id="search-filter" value="{{ $filter }}">
        </div>
    </div>

    <h1>Edit Posts</h1>

    <div class="posts">
        <div class="container">
            @foreach ($posts as $post)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-right">Latest changes:
                          <strong> {{ $post->updated_at ? $post->updated_at->format('F d, Y H:i') : $post->created_at->format('F d, Y H:i') }} </strong>
                        </div>
                        <h4><a href="/post/{{ $post->post_slug }}" target="_blank" title="See the full posting with comments">{{ $post->post_title }}</a></h4>
                        <p>by <em>{{ $post->user->name }}</em>, <a href="mailto:{{ $post->user->email }}">{{ $post->user->email }}</a>:</p>
                        <p>
                        @if ($post->trashed())
                            <span class="label label-warning">Hidden</span>
                        @endif
                        @if (!$post->is_approved)
                            <span class="label label-warning">Not approved</span>
                        @endif
                        </p>
                    </div>
                    <div class="panel-body">
                        <div class="pull-right">
                           <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions
                              <span class="caret"></span></button>
                              <ul class="dropdown-menu">
                                    @if (!$post->trashed())
                                        @if (!$post->is_approved)
                                            <li><a href="#" class="admin-post-approve" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Approve</a></li>
                                        @else
                                            <li><a href="#" class="admin-post-unapprove" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Unapprove</a></li>
                                        @endif
                                        <li><a href="#" class="admin-post-edit" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Edit</a></li>
                                        <li><a href="#" class="admin-post-hide" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Hide</a></li>
                                        <li><a href="#" class="admin-post-delete" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Delete</a></li>
                                    @else
                                        <li><a href="#" class="admin-post-unhide" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Unhide</a></li>
                                        <li><a href="#" class="admin-post-deletehidden" data-id="{{ $post->id }}" data-pageid="{{ $posts->currentPage() }}">Delete</a></li>
                                    @endif
                              </ul>
                            </div>
                        </div>
                        <p>
                            {{ nl2br($post->post_body) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

      <ul class="pagination">
          {{ $posts->links() }}
      </ul>

    </div>
</div>

<div id="admin-post-edit-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Post</h4>
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
