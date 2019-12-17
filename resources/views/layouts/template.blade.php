<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ url('css/simple-sidebar.css') }}" rel="stylesheet">
    <link href="{{ url('css/bootstrap-icon.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/own-style.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
<div class="d-flex" id="wrapper">
@include('layouts.navbar')
<!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn" id="menu-toggle">
                <img src="{{ url('svg/bootstrap-icons/text-indent-right.svg') }}" class="icon-width-sm" alt="menu-icon" title="icon">
            </button>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"> </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Kullanıcı
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Oturumu Kapat</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="page-content">
            <div class="container">
                @yield('content')

            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->
<!-- Bootstrap core JavaScript -->
<script src="{{url('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('js/app.js')}}"></script>
@yield('js')
<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</body>
</html>
