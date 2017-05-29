<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Builder</title>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/materialize.css') }}" media="screen">
    <!--Import main.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/light.css') }}" id="light-styles">
    <link type="text/css" rel="stylesheet alternate" href="{{ asset('css/dark.css') }}" id="dark-styles">
    <!-- Librerias JQuery -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.jscrollpane.css') }}">

    <!-- Scripts -->
    <script type="text/javascript" data-main="js/admin" src="js/libs/require.js"></script>

</head>
<body>

        <!-- Cabecera de la pagina -->
        <header class="col s12 z-depth-5">
            <!-- Estructura del Menu de Opciones -->
            <ul id="dropdown_opciones" class="dropdown-content">
                    @if (Auth::check())
                        <li id="user_name"><a href="#">{{ Auth::user()->name }}</a></li>
                        @if (Auth::user()->hasRole('admin')) 
                        <li id="user_list"><a href="/users">Ver Usuarios</a></li>
                        <li class="divider"></li> 
                        <li id="upload_info"><a>Resetear BD</a></li>
                        @endif
                        <li class="divider"></li> 
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


<div class="row">
    <div id="content_panel" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card white-grey">
                        <div class="card-content">
                            <span class="card-title">Hola {{Auth::user()->name}}</span>
                            <p>Has iniciado sesion! Bienvenido al panel de control de Builder.</p>
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::user()) 
            <div class="row">
                <div class="col s12">
                <div class="card white-grey">
                    <div id="user_maps" class="card-content">
                        <span class="card-title">Mis Mapas</span>
                        <p>Aqui se pueden editar sus mapas personalizados.</p>
                        <div class="maplist collection">
                        </div>
                    </div>
                </div>
              </div>
            </div>
            @if (Auth::user()->hasRole('admin')) 
            <div class="row">
                <div class="col s12">
                    <div class="card white-grey">
                        <div class="card-content">
                            <span class="card-title">Categorias</span>
                            <p>Aqui se listan todas las categorias disponibles para personalizar los mapas</p>
                            <div class="preview center-align">
                                <img class="card-panel hoverable" src="">
                            </div>
                            <div class="categories collection">
                            
                            </div>
                        </div>
                        <div class="card-action">
                            <a id="category_add" href="#">Agregar Categoria</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

</div>

<!-- Cuadros de dialogo usados por la aplicacion -->
    <div id="dialog_list">
        <div id="map_delete" class="modal">
            <div class="modal-content">
                <h4>Borrar Mapa</h4>
                <p>Está a punto de eliminar uno de sus mapas, esta accion <b>no se puede deshacer</b>, ¿Desea eliminar este Mapa?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">No</a>
                <a id="si_borrar_mapa" href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Si</a>
            </div>
        </div>
    </div>
    @if (Auth::user()->hasRole('admin')) 
    <!-- Borrar categoria -->
    <div id="category_delete" class="modal">
        <div class="modal-content">
            <h4>Borrar Caracteristica</h4>
            <p>Está a punto de eliminar una caracteristica personalizable, esta accion <b>no se puede deshacer</b>, ¿Desea eliminar esta caracteristica?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">No</a>
            <a id="si_borrar_categoria" href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Si</a>
        </div>
    </div>
    <div id="dialog_add_category" class="modal">
        <div class="modal-content">
            <h4>Nueva Categoria</h4>
            <form class="col s12" action="#">
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Tileset</span>
                            <input id="file_input" type="file" accept="image/*">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file_path validate" type="text" placeholder="Seleccione una imagen">
                        </div>
                    </div>
                </div>
                
                <div class="row center-align">
                    <img style="max-width: 512px;" id="img_preview" class="card-panel hoverable" src="/img/icons/upload.png">
                </div>
                
                <div class="row">
                    <div class="input-field">
                        <input id="category_name" type="text" class="validate">
                        <label for="category_name">Nombre de la Categoria</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <!--<i id="icon_preview" class="material-icons prefix">textsms</i>-->
                        <input id="category_icon" type="text" class="autocomplete">
                        <label for="category_icon">Material Icon</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <input id="category_empty_tiles" type="number" class="validate">
                        <label for="category_empty_tiles">Tiles vacios</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a id="confirm_new_category" href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
        </div>
    </div>
    @endif
    
    <!-- Templates de la info de los mapas -->
    <div id="map_templates" hidden>
        <a id="dmap" href='#!' class="collection-item avatar">
            <img src="" alt="" class="circle">
            <i class="title"></i>
            <p>First Line <br>
               Second Line
            </p>
            @if (Auth::user() && Auth::user()->hasRole('admin')) 
            <i class="secondary-content delete material-icons">delete</i>
            @endif
            <i class="secondary-content share material-icons">share</i>
            <!--<i class="secondary-content file_download material-icons">file_download</i>-->
            @if (Auth::user() && Auth::user()->hasRole('admin')) 
            <!--<i class="secondary-content modo_edit material-icons">mode_edit</i>-->
            @endif
        </a>
        <a id="umap" href='#!' class="collection-item avatar">
            <img src="" alt="" class="circle">
            <i class="title"></i>
            <p>First Line <br>
               Second Line
            </p>
            <i class="secondary-content delete material-icons">delete</i>
            <i class="secondary-content share material-icons">share</i>
            <!--<i class="secondary-content file_download material-icons">file_download</i>
            <i class="secondary-content modo_edit material-icons">mode_edit</i>-->
        </a>
    </div>

</body>
</html>

