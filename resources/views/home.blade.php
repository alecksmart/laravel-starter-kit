@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <ul>
                        <li><a href="/myaccount">My Account</a></li>
                        <li><a href="/posts">List of Post</a></li>
                        <li><a href="/comments">List of Comments</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
