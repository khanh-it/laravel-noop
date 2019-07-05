@push('MainPanel')
    @php ob_start() @endphp
        @include('jqxWindowFooterBtns')
    @php $windowFooterBtns = trim(ob_get_clean()) @endphp
{{-- ads --}}
    {{-- grid --}}
    {!! $jqxGrid->html() !!}
    {{-- window content --}}
    @php ob_start() @endphp
        @include('ads.form1st')
    @php $windowBody = trim(ob_get_clean()) @endphp
    {!! $jqxWindow->html([
        'body' => $windowBody,
        'footer' => $windowFooterBtns
    ]) !!}
{{-- .end#ads --}}
@endpush
@push('script')
    <script>
        {!! $jqxGrid !!}
        {!! $jqxWindow !!}
    </script>
    <script src="{{ asset('js/app/ads/index.js') }}"></script>
@endpush
{{-- layout --}}
@php
JqxLayout::setOptions([
    'hide_bottom_panels' => true
]);
@endphp
@extends('jqxLayout')
