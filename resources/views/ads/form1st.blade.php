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
        {!! Form::label('ads_id', ($label = "ID"), ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('ads_id', null, [
                'id' => 'ads_id',
                'class' => 'form-control',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('ads_name', ($label = "Ads name") . " {$required}", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('ads_name', null, [
                'id' => 'ads_name',
                'class' => 'form-control',
                'placeholder' => $label,
                'maxlength' => '255',
                'required' => 'required'
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('ads_specs', ($label = "Ads size") . " {$required}", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            <div class="row">
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">W</span>
                        {!! Form::number('ads_spec_width', 0, [
                            'id' => 'ads_spec_width',
                            'class' => 'form-control text-right',
                            'maxlength' => '10',
                            'required' => 'required'
                        ]) !!}
                        <span class="input-group-addon">px</span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">H</span>
                        {!! Form::number('ads_spec_height', 0, [
                            'id' => 'ads_spec_height',
                            'class' => 'form-control text-right',
                            'maxlength' => '10',
                            'required' => 'required'
                        ]) !!}
                        <span class="input-group-addon">px</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('ads_uses', ($label = "Lượt click") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::number('ads_uses', 0, [
                'id' => 'ads_uses',
                'class' => 'form-control text-right',
                'placeholder' => $label,
                'maxlength' => '10',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('ads_note', ($label = "Ghi chú") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('ads_note', null, [
                'id' => 'ads_note',
                'class' => 'form-control',
                'placeholder' => $label,
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback" data-route="update">
        {!! Form::label('ads_status', ($label = "Sử dụng?") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::select('ads_status', \App\Models\Ads::statusList(), null, [
                'class' => 'form-control input-sm'
            ]) !!}
        </div>
    </div>
{!! Form::close() !!}
