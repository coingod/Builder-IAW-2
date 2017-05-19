<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Builder') }}</title>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.css" media="screen">
    <!--Import main.css-->
    <link type="text/css" rel="stylesheet" href="css/light.css" id="light-styles">
    <link type="text/css" rel="stylesheet alternate" href="css/dark.css" id="dark-styles">
    <!-- Librerias JQuery -->
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.css">

</head>
<body>

        <!-- Cabecera de la pagina -->
        <header class="col s12 z-depth-5">
            <!-- Estructura del Menu de Opciones -->
            <ul id="dropdown_opciones" class="dropdown-content">
                    @if (Auth::check())
                        <li id="user_name"><a href="#">{{ Auth::user()->name }}</a></li>
                        <li id="user_logout">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Cerrar Sesion
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @else
                        <li id="user_login"><a href="{{ url('/login') }}">Iniciar Sesion</a></li>
                        <li id="user_register"><a href="{{ url('/register') }}">Registrarse</a></li>
                    @endif
            </ul>
            <!-- Barra de titulo/navegacion -->
            <nav>
                <div class="nav-wrapper">
                    <a href="{{ url('/') }}" class="brand-logo"><i class="material-icons">account_balance</i>BUILDER</a>
                    <ul class="right hide-on-med-and-down">
                        <!-- Trigger del Menu de Opciones-->
                        <li><a class="dropdown-button" href="#!" data-activates="dropdown_opciones">@if (Auth::guest()) Visitante @else {{ Auth::user()->name }} @endif<i class="material-icons right">more_vert</i></a></li>
                    </ul>
                </div>
            </nav>
        </header>

        @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('js/libs/jquery.js') }}"></script>
    <script src="{{ asset('js/libs/materialize.js') }}"></script>
</body>
</html>
