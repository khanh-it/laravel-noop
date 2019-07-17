@php
    $required = '<span class="bg-danger text-danger">(*)</span>';
    $controlLabel = 'col-xs-3 control-label';
@endphp
{!! Form::open([
    'id' => 'form1st',
    'class' => 'form-horizontal targetform',
    'enctype' => 'application/x-www-form-urlencoded',
]) !!}
    <div class="form-group gen-feedback hidden" hidden data-route="update">
        {!! Form::label('tag_id', ($label = "ID"), ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('tag_id', null, [
                'id' => 'tag_id',
                'class' => 'form-control',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('tag_name', ($label = "Tag name") . " {$required}", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('tag_name', null, [
                'id' => 'tag_name',
                'class' => 'form-control',
                'placeholder' => $label,
                'maxlength' => '255',
                'required' => 'required'
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback hidden" hidden>
        {!! Form::label('tag_uses', ($label = "Số lần dùng") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::number('tag_uses', 0, [
                'id' => 'tag_uses',
                'class' => 'form-control text-right',
                'placeholder' => $label,
                'maxlength' => '10',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('tag_note', ($label = "Ghi chú") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('tag_note', null, [
                'id' => 'tag_note',
                'class' => 'form-control',
                'placeholder' => $label,
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback" data-route="update">
        {!! Form::label('tag_status', ($label = "Sử dụng?") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::select('tag_status', \App\Models\Tag::statusList(), null, [
                'class' => 'form-control input-sm'
            ]) !!}
        </div>
    </div>
{!! Form::close() !!}
