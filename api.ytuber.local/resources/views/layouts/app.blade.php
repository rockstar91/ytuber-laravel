<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if(Auth::check()) <meta name="user-id" content="{{ Auth::user()->id }}"> @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="lypUBECgFEtE8MU1baAkbs4fEaVulmUUiK87R0O8EHk" />
    <title>{{ config('app.name', 'Socbooster') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    {{--<script src="{{ asset('/js/main/jquery.min.js') }}"></script>--}}
    {{--    <link href="{{asset('/vendor/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css">--}}
    <link href="{{asset('/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    {{--<link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('/css/layout.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('/css/colors.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('/css/components.min.css')}}" rel="stylesheet" type="text/css">--}}
    <link href="{{asset('/css/all.css')}}" rel="stylesheet" type="text/css">
    {{--<link href="{{asset('/css/app.css')}}" rel="stylesheet" type="text/css">--}}
    <script src="{{ asset('/js/app2.js') }}"></script>
    {{--<script src="{{ asset('/js/main/bootstrap.bundle.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/plugins/loaders/blockui.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/plugins/forms/styling/switchery.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/plugins/forms/styling/uniform.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/plugins/ui/sticky.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/plugins/ui/prism.min.js') }}"></script>--}}
    {{--<script src="{{ asset('/js/application.js') }}"></script>--}}
</head>
<body class="sidebar-xs">
<div  id="app">
@include('inc.navbar')
<!-- Page container -->
    <!-- Page content -->
    <div class="page-content">
        {{--        @include('inc.messages')--}}
        @auth
            @include('inc.sidebar')
            @include('inc.sidebar2')
        @endauth
        <div class="page-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
