@extends('..layouts.app')

@section('content')

<div class="container">

<h1>My Account</h1>

@foreach($errors->all() as $error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endforeach

<p class="pull-right">
    @if(!$user->avatar_filename )
        <img data-src="holder.js/200x200" class="img-thumbnail rounded float-right" alt="200x200" style="width: 200px; height: 200px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_15a93a1caf7%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_15a93a1caf7%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2275.5%22%20y%3D%22103.6%22%3E200x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
    @else
        <img class="img-thumbnail rounded float-right" alt="200x200" style="width: 200px; height: 200px;" src="/{{ Config::get('constants.AVATAR_WEB_PATH') }}/{{ $user->avatar_filename }}">
    @endif
</p>

<h2>Avatar Upload</h2>

{{ Form::open(['url'=>'/user/avatar','method'=>'POST', 'files'=>true]) }}

    <p>We recommend — best looking avatars with this site are square ones.</p>

    <div class="form-errors"></div>

    <div class="form-group">
        {{ Form::file('image') }}
    </div>
    <div class="form-group">
        {{ Form::submit('Submit', array('class'=>'send-btn')) }}
    </div>

{{ Form::close() }}

<h2>Personal Data</h2>

{{ Form::model($user, ['url'=>'/myaccount','method'=>'POST']) }}

    <p>* - required fields</p>
    <p>** - fill in only if you are changing your password</p>
    <div class="form-errors"></div>

    <div class="form-group">
        {!! Form::label('Name*') !!}
        {!! Form::text('name', null, array('required',
                'class'=>'form-control', 'placeholder'=>'Name')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('E-mail Address*') !!}
        {!! Form::text('email', null, array('required',
                'class'=>'form-control', 'placeholder'=>'E-mail address')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Password**') !!}
        {!! Form::password('password', array('class'=>'form-control')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Confirm Password**') !!}
        {!! Form::password('password_confirmation', array('class'=>'form-control')) !!}
    </div>

    <div class="form-group">
        {{ Form::submit('Save', array('class'=>'send-btn')) }}
    </div>

{{ Form::close() }}

</div>

@endsection
