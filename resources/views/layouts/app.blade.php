@section('header')@include('layouts.app[header]')@endsection
@section('footer')@include('layouts.app[footer]')@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- +++ bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> --}}
    <!-- +++ jqwidgets -->
    <link href="{{ asset('jqwidgets/styles/jqx.base.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jqwidgets/styles/jqx.bootstrap.min.css') }}" rel="stylesheet">
    <!-- +++ app -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('style')
</head>
<body class="clearfix">
    <div id="app" class="clearfix">
        {{-- header --}}@yield('header'){{-- /header --}}
        <main id="main" class="clearfix">
            @stack('content')
        </main>
        {{-- footer --}}@yield('footer'){{-- /footer --}}
    </div>
    <!-- Scripts -->
    <!-- +++ bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="{{ asset('js/locutus/php/strings/number_format.js') }}"></script>
    <!-- +++ app -->
    @stack('script.before')
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('script')
</body>
</html>
