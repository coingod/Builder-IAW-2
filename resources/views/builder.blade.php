<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Builder IAW</title>
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

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

    <div id="loading_screen">
        <div>
            <div class="z-depth-5">
                <div>
                    <h1 class="center"><i class="material-icons">account_balance</i>BUILDER</h1>
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                    <p class="center">Cargando...</p>
                </div>
            </div>
        </div>
    </div>

    <main class="row" oncontextmenu="return false;">

        <!-- Cabecera de la pagina -->
        <header class="col s12 z-depth-5">
            <!-- Estructura del Menu de Cuenta de usuario -->
            <ul id="dropdown_account" class="dropdown-content">
                @if (Route::has('login'))
                <li><a href="#!"><b>@if (Auth::guest()) Visitante @else {{ Auth::user()->name }} @endif</b></a></li>
                    @if (Auth::check())
                        <li id="user_account"><a href="{{ url('/home') }}">Mi cuenta</a></li>
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
                @endif
            </ul>
            <!-- Estructura del Menu de Mapa -->
            <ul id="dropdown_mapas" class="dropdown-content">
                <li><a href="#!"><b>Mapa</b></a></li>
                <li id="new_map"><a href="#!">Nuevo</a></li>
                <li class="divider"></li>
                @if (Auth::user())
                <li id="save_map"><a>Guardar</a></li>
                @else
                <li class="divider"></li>
                <li id="save_in_local_storage"><a>Guardar Local</a></li>
                <li id="load_from_local_storage"><a>Cargar Local</a></li>
                @endif
                <li id="open_maps_library"><a href="#maps_library">Catalogo</a></li>
                <li class="divider"></li>
                <li id="export_map"></li>
                <li id="import_map"><a href="#!">Importar JSON</a></li>
                <li class="divider"></li>
                <li id="export_png"><a href="#!">Exportar PNG</a></li>
            </ul>
            <!-- Estructura del Menu de Opciones -->
            <ul id="dropdown_opciones" class="dropdown-content">
                <li><a href="#!"><b>Interfaz</b></a></li>
                <li id="light_theme"><a href="#!">Light</a></li>
                <li id="dark_theme"><a href="#!">Dark</a></li>
                <li class="divider"></li>
                <li id="about"><a href="#!"><b><u>Autores</u></b></a></li>
                <li><a href="/readme" target="_blank"><b><u>Readme</u></b></a></li>
            </ul>
            <!-- Barra de titulo/navegacion -->
            <nav>
                <div class="nav-wrapper">
                    <a href="#!" class="brand-logo"><i class="material-icons">account_balance</i>BUILDER</a>
                    <ul class="right hide-on-med-and-down">
                        <!-- Trigger del Menu de Opciones-->
                        <li><a class="dropdown-button" href="#!" data-activates="dropdown_account">@if (Auth::guest()) Visitante @else {{ Auth::user()->name }} @endif<i class="material-icons right">face</i></a></li>
                        <li><a class="dropdown-button" href="#!" data-activates="dropdown_mapas">Mapa <i class="material-icons right">map</i></a></li>
                        <li><a class="dropdown-button" href="#!" data-activates="dropdown_opciones">Opciones <i class="material-icons right">more_vert</i></a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Panel Editor lateral -->
        <div id="editor" class="col s3 z-depth-5">
            <div id="tilesets">
                <nav>
                    <div class="nav-wrapper">
                        <a href="#!" class="brand-logo">Categorias</a>
                    </div>
                </nav>
                <div class="row">
                    <div class="col s12">
                        <ul id="categorieslist" class="tabs-fixed-width z-depth-1">
                            <!-- En tiempo de ejecucion se genera un item por tileset -->
                        </ul>
                    </div>
                    <div id="tileset_container">
                        <!-- En tiempo de ejecucion se genera una coleccion de tiles para cada tileset -->
                    </div>
                </div>
            </div>

            <div id="layers">
                <nav>
                    <div class="nav-wrapper">
                        <a href="#!" class="brand-logo">Capas</a>
                        <a id="layers_add" class="btn-floating btn-large halfway-fab waves-effect waves-light z-depth-1"> <i class="material-icons">add</i> </a>
                    </div>
                </nav>
                <div id="layerlist" class="collection"></div>
            </div>

        </div>

        <!-- Panel del Canvas/Mapa -->
        <div id="canvas_wrapper" class="col s9" data-map={{$map}}>
            <!-- El Canvas es en donde el usuario coloca los elementos/tiles -->
            <div id="canvas" class="z-depth-3">
                <!-- Dentro de tiles habra una entrada por cada capa, cada capa almacena los tiles que el usuario le asigno -->
                <div id="tiles"></div>
                <!-- Representacion del cursor sobre el canvas -->
                <div class="cursor"></div>
            </div>
        </div>

    </main>

    <!-- Boton de herramientas -->
    <div class="fixed-action-btn vertical click-to-toggle">
        <a id="tools_btn" class="btn-floating btn-large">
            <i class="material-icons">build</i>
        </a>
        <ul id="tools">
            <!-- Modo para dibujar sobre el canvas -->
            <li id="edit_mode"><a class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Editar"><i class="material-icons">mode_edit</i></a></li>
            <!-- Modo para seleccionar elemento del canvas y asignarlo al cursor -->
            <!-- <li id="pick_mode"><a class="btn-floating tooltipped red" data-position="left" data-delay="50" data-tooltip="Pick"><i class="material-icons">colorize</i></a></li> -->
            <!-- Modo para acercar/alejar el canvas -->
            <!-- <li id="zoom_mode"><a class="btn-floating tooltipped yellow darken-1" data-position="left" data-delay="50" data-tooltip="Zoom"><i class="material-icons">zoom_in</i></a></li> -->
            <!-- Modo para mover el canvas -->
            <li id="pan_mode"><a class="btn-floating tooltipped" data-position="left" data-delay="50" data-tooltip="Mover Canvas"><i class="material-icons">pan_tool</i></a></li>
        </ul>
    </div>

      <!-- Modal Structure -->
      <div id="maps_library" class="modal bottom-sheet">
        <div class="modal-content">
        <div class="modal-footer"><h4>Catalogo de Mapas<i><a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Cerrar</a></i></h4></div>
            <div class="row">
            <div class="col s12">
              <ul id="library_tabs" class="tabs tabs-fixed-width">
                <li class="tab col s3"><a class="active" href="#default_maps">Precargados</a></li>
                @if (Auth::user()) <li class="tab col s3"><a href="#user_maps">Mis Mapas</a></li> @endif
              </ul>
            </div>
            <div id="default_maps" class="col s12">
                <div class="maplist collection">
                </div>
            </div>
            @if (Auth::user())
            <div id="user_maps" class="col s12">
                <div class="maplist collection">
                </div>
            </div>
            @endif
          </div>
        </div>
      </div>

    <!-- Cuadros de dialogo usados por la aplicacion -->
    <div id="dialog_list">
        <!-- Informacion del mapa actualmente cargado -->
        <div id="dialog_mapinfo" class="modal">
            <div class="modal-content">
                <h4 id="info_map_name"></h4>
                <p id="info_map_author"></p>
                <p id="info_map_description"></p>
            </div>
            <div class="modal-footer">
                <a id="confirm_new_category" href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
        </div>
        <!-- About -->
        <div id="dialog_about" class="modal">
            <div class="modal-content">
                <h4>Acerca de Builder 2</h4>
                <h5>Proyecto 2 - Ingenieria de Aplicaciones Web 2017</h5>
                <p>Desarrollado por Ignacio del Barrio y Lucas Menchi.</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Cerrar</a>
            </div>
        </div>
        <!-- Guardar Mapa -->
        <div id="dialog_save_map" class="modal">
            <div class="modal-content">
                <h4>Guardar Mapa</h4>
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="map_name" type="text" class="validate">
                            <label for="map_name">Nombre del Mapa</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="map_info" type="text" class="validate">
                            <label for="map_info">Descripcion del Mapa</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
        </div>
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
        <!-- Borrar capa -->
        <div id="layer_delete" class="modal">
            <div class="modal-content">
                <h4>Borrar Capa</h4>
                <p>Está a punto de eliminar una capa, esta accion <b>no se puede deshacer</b>, ¿Desea eliminar esta Capa?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">No</a>
                <a id="si_borrar_capa" href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Si</a>
            </div>
        </div>
        <!-- Importar JSON -->
        <div id="dialog_import" class="modal">
            <div class="modal-content">
                <h4>Importar Mapa</h4>
                <p>Seleccione el JSON correspondiente:</p>
                <form action="#">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Archivo</span>
                            <input id="file-input" type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </form>
            </div>
            <!--
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
            -->
        </div>
        <!-- Crear mapa -->
        <div id="dialog_map" class="modal">
            <div class="modal-content">
                <h4>Crear nuevo Mapa</h4>
                <form>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="cantFilas" type="text" class="validate">
                            <label for="cantFilas">Ingrese cantidad de filas</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="cantColumnas" type="text" class="validate">
                            <label for="cantColumnas">Ingrese cantidad de columnas</label>
                        </div>
                    </div>
                </form>
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Ok</a>
            </div>
            <!--
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
            -->
        </div>
        <!-- Agregar Capa -->
        <div id="dialog_new_layer" class="modal">
            <div class="modal-content">
                <h4>Nueva Capa</h4>
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="layer_name" type="text" class="validate">
                            <label for="layer_name">Nombre de la capa</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
        </div>

        <div id="dialog_share" class="modal">
            <div class="modal-content">
                <form class="col s12">
                    <div class="row">
                        <div class="input-field">
                            <h4>Enlace para compartir</h4>
                            <p id="share_token"> </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Confirmar</a>
            </div>
        </div>

    </div>

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
            <!--<i class="secondary-content file_download material-icons">file_download</i>-->
            <!--<i class="secondary-content modo_edit material-icons">mode_edit</i>-->
        </a>
    </div>
    <!-- data-main attribute tells require.js to load js/main.js after require.js loads. -->
    <script type="text/javascript" data-main="js/main" src="js/libs/require.js"></script>
</body>

</html>
