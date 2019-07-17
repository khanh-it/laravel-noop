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
        {!! Form::label('rpt_id', ($label = "ID"), ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('rpt_id', null, [
                'id' => 'rpt_id',
                'class' => 'form-control',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_type', ($label = "Loại") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('rpt_type', null, [
                'class' => 'form-control input-sm',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_ips', ($label = "IP") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('rpt_ips', null, [
                'id' => 'rpt_ips',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_uri_fr', ($label = "Uri tải") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('rpt_uri_fr', null, [
                'id' => 'rpt_uri_fr',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_uri_to', ($label = "Uri click") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('rpt_uri_to', null, [
                'id' => 'rpt_uri_to',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_ua', ($label = "Platform/Browser") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            <div class="row">
                <div class="col-xs-12">
                {!! Form::textarea('rpt_ua', null, [
                    'id' => 'rpt_ua',
                    'class' => 'form-control',
                    'required' => 'required',
                    'readonly' => 'readonly',
                    'rows' => 3,
                ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">Pl</span>
                        {!! Form::text('ua_platform', null, [
                            'id' => 'ua_platform',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                        ]) !!}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon">Br</span>
                        {!! Form::text('ua_browser', null, [
                            'id' => 'ua_browser',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('rpt_created_at', ($label = "Thời gian") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::text('rpt_created_at', null, [
                'id' => 'rpt_created_at',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
            ]) !!}
        </div>
    </div>
{!! Form::close() !!}
