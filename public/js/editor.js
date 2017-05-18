define([
    "jquery-ui",
    "canvas",
    "tileset",
    "currentState",
    "layers"
], function($, Canvas, Tileset, currentState, Layers) {
    var Editor = {};

    Editor.tool = "edit_mode"; //Que es lo que estoy haciendo, dibujando, rellenando, eliminando
    Editor.mousedown = false; //Mouse presionado

    Editor.Tileset = Tileset.initialize(Editor);
    Editor.Canvas = Canvas.initialize(Editor);
    Editor.Layers = Layers.initialize(Editor);
    Editor.currentState = currentState.initialize(Editor);

    Editor.initialize = function() {
        /*
        //Estado del mouse
        $(document).on("mousedown mouseup", function(e) {
            Editor.mousedown = (e.type == "mousedown") && (e.which == 1);
        });
        */

        //Configuramos la estructura de todos los cuadros de dialogo
        Editor.Layers.crearDialog();
        $("#dialog_info").modal();
        Editor.currentState.crearDialog();
        Editor.Canvas.crearDialog();

        //Oyentes de los botones de herramientas
        $("#edit_mode").on("click", function() {
            $("#tools > li > a").removeClass("pulse");
            $("#edit_mode > a").addClass("pulse");
            Editor.tool = "edit_mode";
            $("#canvas").draggable("disable");
            $("#canvas .cursor").toggle();
        });
        $("#pan_mode").on("click", function() {
            $("#tools > li > a").removeClass("pulse");
            $("#pan_mode > a").addClass("pulse");
            Editor.tool = "pan_mode";
            $("#canvas").draggable("enable");
            $("#canvas .cursor").toggle();
        });

        //Oyentes para cambio de skin de la interfaz
        $("#light_theme").on("click", function() {
            $('link[href="css/dark.css"]').attr('href', 'css/light.css');
            localStorage.oldSkin = 'link[href="css/dark.css"]';
            localStorage.skin = 'css/light.css';
        });
        $("#dark_theme").on("click", function() {
            $('link[href="css/light.css"]').attr('href', 'css/dark.css');
            localStorage.oldSkin = 'link[href="css/light.css"]';
            localStorage.skin = 'css/dark.css';
        });

        //Oyentes para el menu de opciones
        $("#new_map").on("click", function() {
            $("#dialog_map").modal("open");
        });
        $("#import_map").on("click", function() {
            $("#dialog_import").modal("open");
        });

        //Seteamos Modo de edicion por defecto
        $("#edit_mode").click();
        $("#canvas .cursor").toggle();

        //Ajustamos el alto del panel editor
        $("#editor").css({
            height: $(document).height() - $("header").height()
        });

        // Disable selection
        $("#tileset, #canvas_wrapper").disableSelection();

        // Esperamos un tiempo y ocultamos la pantalla de carga
        $("#loading_screen").delay(1000).fadeOut('slow', function() {
            //Desplegamos las herramientas al terminar de cargar
            $('.fixed-action-btn').openFAB();
        });

    };

    //Procesa un archivo JSON y genera un nuevo Mapa con los datos del archivo
    Editor.loadExternal = function() {

        //Obtenemos el JSON que se importo
        var json = Editor.currentState.json;

        //Elimina todas las capas actuales
        Editor.Layers.removeAll();

        //Cargamos las categorias nuevas (Tilesets)
        Editor.Tileset.info = json.tilesetInfo;
        Editor.Tileset.load();

        //Configuramos las dimensiones del canvas
        var filas = json.canvasInfo.height;
        var columnas = json.canvasInfo.width;
        Canvas.setSize(columnas, filas);

        //Procesamos la lista de capas
        json.layersInfo.forEach(function(layer) {
            //Agregamos la capa con su nombre y estado
            Editor.Layers.addLayer(layer.nombre, layer.visible);
            //Agregamos todos los elementos al Canvas
            layer.listaTiles.forEach(function(element) {
                //Formato: [id_tile, id_tileset, canvas_fila, canvas_columna]
                Editor.Canvas.loadElement(element);
            }, this);
        }, this);


    };

    return Editor;
});