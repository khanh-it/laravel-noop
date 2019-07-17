@section('breadcrumb')
<b>Ads Report: </b>{{$model->ads_name}}
 (width: {{numberFormat($model->ads_spec_width)}}px/ height: {{numberFormat($model->ads_spec_height)}}px)
 ({{_("Tải")}}: {{numberFormat($model->ads_uses)}}/ {{_("Xem")}}: {{numberFormat($model->ads_viewed)}}/  {{_("CLick")}}: {{numberFormat($model->ads_clicked)}})
@endsection
@push('MainPanel')
    @php ob_start() @endphp
        @include('jqxWindowFooterBtns')
    @php $windowFooterBtns = trim(ob_get_clean()) @endphp
{{-- ads --}}
    {{-- grid --}}
    {!! $jqxGrid->html() !!}
    {{-- window content --}}
    @php ob_start() @endphp
        @include('ads.formRpt1st')
    @php $windowBody = trim(ob_get_clean()) @endphp
    {!! $jqxWindow->html([
        'body' => $windowBody,
        'footer' => '' // $windowFooterBtns
    ]) !!}
{{-- .end#ads --}}
@endpush
@push('style')
    <style type="text/css">
        .appnav.appnav-left{display:none;visibility:hidden}
        body{padding-left:0}
    </style>
@endpush
@push('script')
    <script>
        {!! $jqxGrid !!}
        {!! $jqxWindow !!}
        {!! $jqxWindowCode !!}
    </script>
    <script src="{{ asset('js/faisalman/ua-parser-0.7.2.min.js') }}"></script>
    <script src="{{ asset('js/app/ads/report.js') }}"></script>
@endpush
{{-- layout --}}
@php
JqxLayout::setOptions([
    'hide_bottom_panels' => true
]);
@endphp
@extends('jqxLayout')
