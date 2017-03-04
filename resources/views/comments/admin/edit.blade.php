{{ Form::model($comment) }}

    <p>* - required fields</p>
    <div class="form-errors"></div>

    <input name="_method" type="hidden" value="PUT">

    {{ Form::hidden('comment_id', $comment->id, array('id' => 'comment_id')) }}

    <div class="form-group">
        {!! Form::label('Message*') !!}
        {!! Form::textarea('comment_body', null, array('required',
            'class'=>'form-control', 'placeholder'=>'Comment Message')) !!}
    </div>

{{ Form::close() }}
