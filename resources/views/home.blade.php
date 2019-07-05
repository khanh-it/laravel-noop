@section('breadcrumb'){{_("Dashboard")}}@endsection
@push('content')
<div class="clearfix">
    {{-- tags --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route("tag::index") }}">{{_("Tag")}}</a>
        </div>
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="app-grid table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Count</th>
                            <th>Latests</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class="text-nowrap text-center">1</td>
                            <td class="text-right">{{numberFormat(0)}}</td>
                            <td class="text-right">{{numberFormat(0)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- /tags --}}
    {{-- ads --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route("ads::index") }}">{{_("Ads")}}</a>
        </div>
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="app-grid table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Count</th>
                            <th>Latests</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class="text-nowrap text-center">1</td>
                            <td class="text-right">{{numberFormat(0)}}</td>
                            <td class="text-right">{{numberFormat(0)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- /ads --}}
</div>
@endpush
{{-- layout --}}
@extends('layouts.app')
{{-- .end#layout --}}
