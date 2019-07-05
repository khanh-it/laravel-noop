{{-- navigation top --}}
@include('layouts.app[nav-top]')
{{-- navigation left --}}
@include('layouts.app[nav-left]')
{{-- breadcrumb --}}
<ol class="app-breadcrumb breadcrumb">
    <li class="active"><a href="javascript:void(0);">@yield('breadcrumb')</a></li>
</ol>
{{-- toolbar --}}
<div id="page-toolbar" class="page-toolbar"></div>
{{-- end.toolbar --}}
