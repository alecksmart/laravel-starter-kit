@extends('layouts.app')

@section('content')

<div class="container">

  <h1>Welcome to the Blog</h1>
  <p class="lead">A basic blog boilerplate</p>

  <div class="posts">
    <div class="container">
    @foreach ($posts as $post)
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="pull-right"> {{ $post->updated_at ? $post->updated_at->format('F d, Y H:i') : $post->created_at->format('F d, Y H:i') }} </div>
          <h4><a href="/post/{{ $post->post_slug }}" title="See the full posting with comments">{{ $post->post_title }}</a></h4> </div>
        <div class="panel-body">
        @if($post->user->avatar_filename)
          <p class="pull-right"> <img class="img-thumbnail rounded" title="{{ $post->user->name }}" alt="{{ $post->user->name }}" style="width: 100px; height: 100px;" src="/{{ Config::get('constants.AVATAR_WEB_PATH') }}/{{ $post->user->avatar_filename }}"> </p>
          @endif
          <p>by <em>{{ $post->user->name }}</em>, <a href="mailto:{{ $post->user->email }}">{{ $post->user->email }}</a>:</p>
          <p> {{ implode(' ', array_slice(explode(' ', $post->post_body), 0, 20)) }} &hellip; </p>
        </div>
      </div>
      @endforeach
      </div>

    <ul class="pagination"> {{ $posts->links() }} </ul>

  </div>


  @can('post-create')

  <div class="container">
    <div class="row">
      <div class="col-md-8">

        <h1>New Blog Post</h1>
        <p>* - required fields</p>
        <a href="#" name="postForm"></a>

        @foreach($errors->all() as $error)
            <div class="alert alert-danger"> {{ $error }} </div>
        @endforeach

        {{ Form::open(array('url' => '/post/create')) }}

        {{ Form::token() }}

        <div class="form-group">
            {{ Form::label('Post Title*') }}
            {{ Form::text('post_title', null, array('required', 'class'=>'form-control', 'placeholder'=>'Post title')) }}
        </div>
        <div class="form-group">
            {{ Form::label('Your Message*') }}
            {{ Form::textarea('post_body', null, array('required', 'class'=>'form-control', 'placeholder'=>'Post message')) }}
        </div>
        <div class="form-group">
            {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}
        </div>
        {{ Form::close() }}

      </div>
    </div>
  </div>

  @endcan

</div>
@endsection
