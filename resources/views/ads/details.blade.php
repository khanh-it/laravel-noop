@section('breadcrumb')
<b>Ads: </b>{{$model->ads_name}} (width: {{numberFormat($model->ads_spec_width)}}px/ height: {{numberFormat($model->ads_spec_height)}}px)
@endsection
@push('MainPanel')
    {{-- form --}}
    {!! Form::open([
        'id' => 'form1st',
        'class' => 'clearfix',
        'enctype' => 'application/x-www-form-urlencoded',
    ]) !!}
        <div id="ads_content-box" class="form-group">
            {!! Form::textarea('ads_content', $model->ads_content, [
                'id' => 'ads_content',
                'class' => 'form-control',
            ]) !!}
        </div>
    {!! Form::close() !!}
@endpush
@push('style')
    <style>
        #form1st{height:100%;margin:0;padding:0;}
        #form1st .form-group{height: 100%;margin:0;padding:0;}
        #form1st textarea{height: 100%;visibility:hidden;}
    </style>
@endpush
@push('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/app/ads/details.js') }}"></script>
@endpush
{{-- layout --}}
@php
JqxLayout::setOptions([
    'hide_bottom_panels' => true
]);
@endphp
@extends('jqxLayout')
