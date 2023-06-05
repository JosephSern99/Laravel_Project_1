<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield("title")</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js?v=1.0.0') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css?v=1.0.0') }}" rel="stylesheet">
	<link href="{{ asset('fontawesome/css/all.min.css?v=5.15.2') }}" rel="stylesheet">
    <link href="{{ asset("bootstrap-datetimepicker/eonasdan-bootstrap-datetimepicker.css?v=4.17.48") }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! url("datatables/dataTables.bootstrap4.css") !!}">
	<link rel="stylesheet" type="text/css" href="{!! url("datatables/responsive.dataTables.min.css") !!}">
	<link href="{{ asset('css/main.css?v=1.1.0') }}" rel="stylesheet">
    @yield("css")
</head>
<body class="bg-white">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light p-0">
            <div class="container">
                <div class="col-12 text-right">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav justify-content-end flex-row">
                        <!-- Authentication Links -->
                        @guest
                        @else
						<li class="nav-item mr-3">
                            <a class="nav-link" href="{!! route("home") !!}">{!! __('label.home') !!}</a>
                        </li>
                        <li class="nav-item mr-3">
                            <span class="nav-link" href="{!! route("home") !!}">Welcome, {{ auth()->user()->User_Logon }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">{!! __('label.logout') !!}</a>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="col-12 text-center">
            <!--<a href="{{ url('/') }}"><img class="img-logo" alt="Logo" src="{{ asset('logo/changcheng1.png?v=1.0.0') }}" style="width: 75px;"></a> image logo-->
        </div>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="LogoutModalTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title" id="LogoutModalTitle">{!! __('label.logout_modal.title') !!}</h6>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">{!! __('message.logoutconfirmation') !!}</div>
                <div class="modal-footer">
                    <form action="{!! route('logout') !!}" method="post">
                    @csrf
                    <button class="btn btn-teal" type="button" data-dismiss="modal">{!! __('label.cancel') !!}</button>
                    <button class="btn btn-primary" type="submit">{!! __('label.logout') !!}</button>
                    </form>
                </div>
            </div>
            </div>
        </div>

        <main class="py-1">
            @include("alert")
            @yield('content')
        </main>
    </div>
    <script src="{!! asset("js/moment.js?v=0.0.1") !!}"></script>
    <script src="{!! asset("bootstrap-datetimepicker/eonasdan-bootstrap-datetimepicker.js?v=0.0.01") !!}"></script>
	<script src="{!! url("datatables/jquery.dataTables.js") !!}"></script>
	<script src="{!! url("datatables/dataTables.bootstrap4.js") !!}"></script>
	<script src="{!! url("datatables/dataTables.responsive.min.js") !!}"></script>
    <script src="{!! asset("js/main.js?v=1.0.2") !!}"></script>
	<script src="{!! asset("js/sign/excanvas.js") !!}"></script>
	<script src="{!! asset("js/sign/signature_pad.min.js") !!}"></script>


    @yield("script")
</body>
</html>
