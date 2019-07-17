<div id="app-header" class="clearfix">
    {{-- navigation top --}}
    @include('layouts.app[nav-top]')
    {{-- navigation left --}}
    @include('layouts.app[nav-left]')
    @auth
    {{-- breadcrumb --}}
    <ol class="app-breadcrumb breadcrumb">
        <li class="active"><a href="">@yield('breadcrumb')</a></li>
    </ol>
    {{-- toolbar --}}
    <div id="page-toolbar" class="page-toolbar"></div>
    {{-- end.toolbar --}}
    @endauth
</div>
