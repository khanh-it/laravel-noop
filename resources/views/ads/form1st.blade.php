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
        {!! Form::label('rpt', ($label = "Thống kê") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            <div class="row">
                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">L.Tải</span>
                        {!! Form::number('ads_uses', 0, [
                            'id' => 'ads_uses',
                            'class' => 'form-control text-right',
                            'placeholder' => $label,
                            'maxlength' => '10',
                        ]) !!}
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">L.Xem</span>
                        {!! Form::number('ads_viewed', 0, [
                            'id' => 'ads_viewed',
                            'class' => 'form-control text-right',
                            'placeholder' => $label,
                            'maxlength' => '10',
                        ]) !!}
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="input-group">
                        <span class="input-group-addon">L.Click</span>
                        {!! Form::number('ads_clicked', 0, [
                            'id' => 'ads_clicked',
                            'class' => 'form-control text-right',
                            'placeholder' => $label,
                            'maxlength' => '10',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('tags', ($label = "Tags") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('tags', null, [
                'id' => 'tags',
                'class' => 'form-control',
                'placeholder' => $label,
                'rows' => 3,
            ]) !!}
        </div>
    </div>
    <div class="form-group gen-feedback">
        {!! Form::label('links', ($label = "Links") . "", ['class' => $controlLabel], false) !!}
        <div class="col-xs-9">
            {!! Form::textarea('links', null, [
                'class' => 'form-control',
                'placeholder' => $label,
                'rows' => 3,
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
                'rows' => 2,
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
