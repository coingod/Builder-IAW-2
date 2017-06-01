define([
    "jquery-ui",
    "canvas",
    "tileset",
    "currentState",
    "layers",
    "maps"
], function($, Canvas, Tileset, currentState, Layers, Maps) {
    var Editor = {};

    Editor.tool = "edit_mode"; //Que es lo que estoy haciendo, dibujando, rellenando, eliminando
    Editor.mousedown = false; //Mouse presionado

    Editor.Tileset = Tileset.initialize(Editor);
    Editor.Canvas = Canvas.initialize(Editor);
    Editor.Layers = Layers.initialize(Editor);
    Editor.currentState = currentState.initialize(Editor);
    Editor.Maps = Maps.initialize(Editor);

    Editor.initialize = function() {

        //Configuramos la estructura de todos los cuadros de dialogo
        Editor.Layers.crearDialog();
        Editor.Maps.crearDialog();
        $("#dialog_about").modal();
        $("#dialog_mapinfo").modal();
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
        $("#save_in_local_storage").on("click", function(){
          Editor.currentState.loadCurrentState();
          localStorage.json=JSON.stringify(Editor.currentState.json);
        });
        $("#load_from_local_storage").on("click", function(){
          Editor.currentState.json=JSON.parse(localStorage.json);
          Editor.loadExternal();
        });

        $("#upload_info").on("click", function(){
          Editor.currentState.loadCurrentState();
          Editor.currentState.json.layersInfo={}; //No necesitamos enviar esto, lo obviamos!
          $.ajax({ method: "POST", url: "/resetDB", data:Editor.currentState.json });
        });

       $("#new_map").on("click", function() {
            $("#dialog_map").modal("open");
        });
        $("#import_map").on("click", function() {
            $("#dialog_import").modal("open");
        });
        $("#export_png").on("click", function() {
            //$("#dialog_import").modal("open");
            //Generamos la imagen PNG y la abrimos en ventana nueva (NewWindow: true)
            Editor.Canvas.createPNG(true, 0);
        });
        $("#about").on("click", function() {
            $("#dialog_about").modal("open");
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

        //Share
        var token=$("#canvas_wrapper").attr("data-map");
        //console.log("Hellouu "+token);
        if(token!="new"){
          //Es un token compartido
          $.ajax({ method: "GET", url: "/getmap/" + token, success: function(response){
              //console.log(response);
              //Si la respuesta es vacia, no es un token valido
              if(response.length == 0){
                $("#info_map_name").text("Error al cargar el mapa");
                $("#info_map_author").html("El token del enlace ("+token+") no corresponde a un mapa valido. <br>Si el enlace lo obtuvo de otra persona, solicitele uno nuevo.");
                $("#dialog_mapinfo").modal("open");
                //console.log("Token invalido");
                return;
              }
              Editor.currentState.json=response.toReturn;
              $.ajax({ method: "GET", url: "/user/" + Editor.currentState.json.userId, success: function(response){
                $("#info_map_name").text(Editor.currentState.json.nombre);
                $("#info_map_author").html("<b>Autor:</b> " + response.user.name + "<br>" + response.user.email );
                $("#dialog_mapinfo").modal("open");
              }});

              Editor.loadExternal();
          }});
        };
    };

    //Procesa un archivo JSON y genera un nuevo Mapa con los datos del archivo
    Editor.loadExternal = function() {

        //Obtenemos el JSON que se importo
        var json = Editor.currentState.json;
        //console.log(json);

        //Elimina todas las capas actuales
        Editor.Layers.removeAll();

        //Cargamos las categorias nuevas (Tilesets)
        Editor.Tileset.info = json.tilesetInfo;
        Editor.Tileset.load();

        //Configuramos las dimensiones del canvas
        var filas = json.height;
        var columnas = json.width;
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
