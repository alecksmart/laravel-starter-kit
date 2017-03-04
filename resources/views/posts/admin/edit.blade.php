{{ Form::model($post) }}

    <p>* - required fields</p>
    <div class="form-errors"></div>

    <input name="_method" type="hidden" value="PUT">

    {{ Form::hidden('post_id', $post->id, array('id' => 'post_id')) }}

    <div class="form-group">
        {!! Form::label('Post Title*') !!}
        {!! Form::text('post_title', null, array('required',
                'class'=>'form-control', 'placeholder'=>'Post title')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Message*') !!}
        {!! Form::textarea('post_body', null, array('required',
            'class'=>'form-control', 'placeholder'=>'Post message')) !!}
    </div>

{{ Form::close() }}
