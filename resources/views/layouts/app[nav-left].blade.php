@php
// Current request url
$curRouteName = Route::currentRouteName();
@endphp
{{-- appnav-left --}}
<nav class="appnav appnav-default appnav-left">
    <ul class="nav nav-pills nav-stacked">
        @php
            $route = route($routeName = "home");
            $isActive = ($curRouteName == $routeName);
        @endphp
        <li role="presentation" class="{{$isActive ? "active" : ""}}">
            <a href="{{ $route }}">{{ _("Dashboard") }}</a>
        </li>
        @php
            $route = route($routeName = "ads::index");
            $isActive = ($curRouteName == $routeName);
        @endphp
        <li role="presentation" class="{{$isActive ? "active" : ""}}">
            <a href="{{ $route }}">{{ _("Ads") }}</a>
        </li>
        @php
            $route = route($routeName = "tag::index");
            $isActive = ($curRouteName == $routeName);
        @endphp
        <li role="presentation" class="{{$isActive ? "active" : ""}}">
            <a href="{{ $route }}">{{ _("Tag") }}</a>
        </li>
        {{-- <li role="presentation" class="nav-submenu">
            <a data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Submenus</a>
            <ul id="collapseExample" class="nav nav-stacked collapse">
                <li role="presentation" class="active">
                    <a href="#Home">Home</a>
                </li>
                <li role="presentation">
                    <a href="#Profile">Profile</a>
                </li>
            </ul>
        </li> --}}
    </ul>
</nav>
@php
unset($curRouteName, $route, $routeName, $isActive);
@endphp
{{-- /appnav-left --}}
