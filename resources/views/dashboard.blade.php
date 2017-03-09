@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>
        <div class="panel-body">
          <ul>
            @can('manage-users-list')
              <li><a href="/manage/users/list">Manage Users</a></li>
            @endcan
            @can('manage-posts')
              <li><a href="/manage/posts/list">Manage Posts</a></li>
            @endcan
            @can('manage-comments')
              <li><a href="/manage/comments/list">Manage Comments</a></li>
            @endcan
            <li><a href="/myaccount">My Account</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
