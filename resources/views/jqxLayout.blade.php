@php
// Get, +init default jqx layout
$jqxLayout = JqxLayout::initDefaultLayout();
@endphp
@prepend('content')
    <div id="jqxLoader"></div>
    <div id="jqxLayout">
        {{--The panel content divs can have a flat structure--}}
        {{--documentGroup--}}
        <div data-container="MainPanel" style="visibility: hidden;">
            @stack('MainPanel')
        </div>
        {{--bottom tabbedGroup--}}
        {{--<div data-container="ErrorListPanel"></div>--}}
        <div data-container="SubPanel" style="visibility: hidden;">
            @stack('SubPanel')
        </div>
        {{--right tabbedGroup--}}
        {{--<div data-container="SolutionExplorerPanel">Solution structure</div>
        <div data-container="PropertiesPanel">List of properties</div>--}}
    </div>
@endprepend
{{-- css --}}
@prepend('style')
    <link href="{{ asset('css/jqx.css') }}" rel="stylesheet">
@endprepend
{{-- .end#css --}}
{{-- js --}}
@push('script.before')
    <!-- +++ jqwidgets -->
    <script src="{{ asset('jqwidgets/jqx-all.js') }}"></script>
@endpush
@prepend('script')
    <script>
        {!! $jqxLayout !!}
    </script>
    <script src="{{ asset('js/jqx-init.js') }}"></script>
@endprepend
@push('script')
    <script src="{{ asset('js/jqx.js') }}"></script>
@endpush
{{-- .end#js --}}
{{-- layout --}}
@extends('layouts.app')
