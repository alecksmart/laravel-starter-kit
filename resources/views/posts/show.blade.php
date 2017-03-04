@extends('..layouts.app')

@section('content')

<div class="container">

    <h1>Welcome to the Blog</h1>
    <p class="lead">Basic blog for test task 3.</p>
    <p>
        <a href="/">back to posts list</a>
    </p>

    <div class="posts">
        <div class="container">

          <h2>{{ $post->post_title }}</h2>
          <p>
            by <em>
                {{ $post->user->name }}, <a href="mailto:{{ $post->user->email }}">{{ $post->user->email }}</a>
            </em>
          </p>

          <div class="panel panel-default">
            <div class="panel-body">
              {{ nl2br($post->post_body) }}
            </div>
          </div>

          <h3>Comments:</h3>

          @if (!$post->comments->count())
              No comments yet, be the first to add one.
          @else
            @foreach($post->comments as $comment)
              <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $comment->created_at }}
                    <p>
                        by <em>
                            {{ $comment->user->name }}, <a href="mailto:{{ $post->user->email }}">{{ $comment->user->email }}</a>
                        </em>
                    </p>
                </div>
                <div class="panel-body">{{ $comment->comment_body }}</div>
              </div>
            @endforeach
          @endif

        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <h1>New Post Comment</h1>
            <p>* - required fields</p>
            <a href="javascript:history.back()" name="commentForm"></a>


            @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach


            {{ Form::open(array('url' => '/comments')) }}

                {{ Form::token() }}

                <div class="form-group">
                    {!! Form::label('Your Message*') !!}
                    {!! Form::textarea('comment_body', null, array('required',
                        'class'=>'form-control', 'placeholder'=>'Post message')) !!}
                </div>

                {{ Form::hidden('post_id', $post->id, array('id' => 'post_id')) }}

                <div class="form-group">
                    {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}
                </div>

            {{ Form::close() }}

        </div>
    </div>

</div>
@endsection
