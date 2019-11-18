@section('DOM.body.class'){{'fullwidth fullheight'}}@endsection
@section('breadcrumb')
<span class="label label-primary">Ads Report:</span>&nbsp;
@if ($model)
<span class="label label-info">{{$model->ads_name}}</span>
<span class="label label-success">
(width: {{numberFormat($model->ads_spec_width)}}px/ height: {{numberFormat($model->ads_spec_height)}}px)
 ({{_("Tải")}}: {{numberFormat($model->ads_uses)}}/ {{_("Xem")}}: {{numberFormat($model->ads_viewed)}}/  {{_("CLick")}}: {{numberFormat($model->ads_clicked)}})
</span>
@else
<span class="label label-warning">tất cả dữ liệu</span>
@endif
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
