@section('breadcrumb'){{_("Dashboard")}}@endsection
@push('content')
<div class="clearfix">
    <div class="panel panel-default">
        <div class="panel-heading">{{_("Dashboard")}}</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- app toolbars top --}}
            @include('layouts.app[toolbars]', [ 'pos' => 'top' ])
            {{-- app grid --}}
            <div class="app-grid table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Column heading</th>
                            <th>Column heading</th>
                            <th>Column heading</th>
                            <th width="100px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @for($i = 0; $i < 25; $i++)
                        <tr class="">
                            <td class="text-nowrap text-center">{{$i + 1}}</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td class="text-nowrap text-center">
                                @include('layouts.app[grid-toolbars]', [])
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>
            {{-- app toolbars bottom --}}
            @include('layouts.app[toolbars]', [ 'pos' => 'bottom' ])
            {{-- app pagination --}}
            @include('layouts.app[pagination]', [])
        </div>
    </div>
</div>
@endpush
{{-- layout --}}
@extends('layouts.app')
{{-- .end#layout --}}
