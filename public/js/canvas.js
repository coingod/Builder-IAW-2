define([
    "jquery-ui",
    "jquery.draggable"
], function($) {

    var Canvas = {},
        Editor, tw, th;

    //Coordenadas actuales del cursor en el canvas
    Canvas.cx = 0;
    Canvas.cy = 0;


    Canvas.initialize = function(editor) {
        Editor = editor;

        //Obtenemos el Tileset que esta activo
        var tileset = Editor.Tileset;

        //Obtenemos las dimensiones de los tiles
        tw = tileset.info.tw;
        th = tileset.info.th;

        //Comportamiento del Cursor
        $("#canvas").on("mousedown mousemove", function(event) {

            //Obtenemos el offset del canvas con respecto al documento
            var offset = $("#canvas").offset();

            //Calculamos las coordenadas del cursor con respecto al canvas
            //Normalizadas en funcion de las dimensiones de los tiles/celdas
            //para poder manejarlas como una matriz de tiles
            var x = Math.floor((event.pageX - offset.left) / tw);
            var y = Math.floor((event.pageY - offset.top) / th);

            Canvas.cx = x;
            Canvas.cy = y;

            //Modificamos el CSS para reflejar la posicion del cursor
            //Pasamos de coordenadas normalizadas a coordenadas reales
            $("#canvas").find(".cursor").css({
                top: y * th,
                left: x * tw,
            });

            //Si el usuario hace click
            if (event.type == "mousedown") {
                //click izquierdo
                if (event.which == 1) {
                    //Dibujamos el tile actual en el canvas
                    $("#canvas").find(".cursor").css({
                        opacity: "1",
                    });
                    Canvas.draw();
                    $("#canvas").find(".cursor").css({
                        opacity: "0.4",
                    });
                }
                //click derecho
                else if (event.which == 3) {
                    //Removemos el tile actual en el canvas
                    Canvas.remove();
                }
            }
        });

        //Configuramos el tamaño por defecto
        Canvas.setSize(13, 10);

        //Configuramos el Canvas para que sea posible moverlo
        $("#canvas").draggable({
            disabled: true,
            cursor: "move",
        });

        return this;
    };

    Canvas.setSize = function(w, h) {
        //Modificamos el tamaño del mapa en funcion del tamaño de los tiles
        $("#canvas").css({
            width: w * tw, //13
            height: h * th //10
        });

        //Dibujamos la grilla del mapa
        Canvas.updateGrid();
        Canvas.updatePosition();
    }

    Canvas.crearDialog = function() {
        $("#dialog_map").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                    Editor.Layers.removeAll();
                    var filas = $("#cantFilas").val();
                    var col = $("#cantColumnas").val();
                    Canvas.setSize(col, filas);
                    Editor.Layers.createDefaultLayers();
                } // Callback for Modal close
        });
    };

    //Dibuja en la capa actual un elemento especificado
    Canvas.loadElement = function(tileData) {
        //Formato: [id_tile, id_tileset, canvas_fila, canvas_columna]
        var id_tile = tileData[0];
        var id_ts = tileData[1];
        var fila = tileData[2];
        var col = tileData[3];

        //Consultamos las dimensiones del tileset
        var ts_width = Editor.Tileset.info.categories[id_ts].width; //$("#tileset_" + id_ts).attr("data-size");
        var ts_height = Editor.Tileset.info.categories[id_ts].height;

        //Mapeamos el id del tile al offset dentro de la imagen en filas y columnas (tileset)
        var ofx = -((id_tile) % (ts_width / tw)); //col
        var ofy = -(Math.floor((id_tile) / Math.floor(ts_width / tw))); //fila
        //Preparo el atributo con las coordenadas normalizadas
        var coords = fila + "." + col;

        //Debemos crear un div con el atributo data-coords
        tile = $("<div data-coords='" + coords + "'></div>");
        //Agregamos al CSS la posicion
        tile.css({
            position: "absolute",
            left: fila * tw + "px",
            top: col * th + "px",
        });
        //Agregamos al CSS la id del tileset
        $(tile).addClass("ts_" + id_ts);
        //Agregamos al CSS el offset del tile en el tileset
        tile.css("background-position", ofx * tw + "px " + ofy * th + "px");
        //Obtenemos la capa actualmente activa
        var currentLayer = Editor.Layers.currentLayer();
        //Agregamos el nuevo elemento a la capa
        $(currentLayer.layer).append(tile);
    };

    //Dibujamos el elemento selecionado del tileset en la posicion del cursor de la capa actual
    Canvas.draw = function() {

        //Si no estamos en modo edicion, no hacemos nada
        if (Editor.tool != "edit_mode") return;

        //Obtenemos la capa actualmente activa
        var currentLayer = Editor.Layers.currentLayer();

        //Calculamos la posicion del cursor
        var cxp = Canvas.cx * tw;
        var cyp = Canvas.cy * th;

        var ts_id;

        //Obtenemos el offset del tile seleccionado del tileset
        var offset = $("#canvas .cursor").css("background-position").split(" ");
        var ofx = parseInt(offset[0], 10);
        var ofy = parseInt(offset[1], 10);

        //Preparo el atributo con las coordenadas normalizadas actuales
        var coords = Canvas.cx + "." + Canvas.cy;
        //Busco en la capa actual algun div con las coordenadas del cursor
        var div = $(currentLayer.layer).find("div[data-coords='" + coords + "']");
        //Si encontre un resultado, entonces ya tengo un tile almacenado
        var tile = div;


        //Si no hay resultado quiere decir que aun no hay nada en esta posicion
        if (div.length == 0) {
            //Debemos crear un div con el atributo data-coords
            tile = $("<div data-coords='" + coords + "'></div>");
            //Agregamos al CSS la posicion
            tile.css({
                position: "absolute",
                left: cxp + "px",
                top: cyp + "px",
            });
            //Obtenemos el tileset de la seleccion actual
            ts_id = $("#canvas .cursor").attr("data-ts");
            $(tile).addClass("ts_" + ts_id);
            //Agregamos al CSS el offset del tile en el tileset
            tile.css("background-position", ofx + "px " + ofy + "px");
            //Agregamos el nuevo elemento a la capa
            $(currentLayer.layer).append(tile);
        } else {
            tile.css({
                position: "absolute",
                left: cxp + "px",
                top: cyp + "px",
            });
            //Obtenemos el tileset de la seleccion actual
            ts_id = $("#canvas .cursor").attr("data-ts");
            //Eliminamos la referencia al tileset anterior
            $(tile).removeClass();
            //Agregamos la nueva referencia
            $(tile).addClass("ts_" + ts_id);
            //Agregamos al CSS el offset del tile en el tileset
            tile.css("background-position", ofx + "px " + ofy + "px");
        }
    };

    //Removemos el elemento en la posicion del cursor de la capa actual
    Canvas.remove = function() {
        //Obtenemos la capa actualmente activa
        var currentLayer = Editor.Layers.currentLayer();
        //Preparo el atributo con las coordenadas normalizadas actuales
        var coords = Canvas.cx + "." + Canvas.cy;
        //Busco en la capa actual algun div con las coordenadas del cursor
        var div = $(currentLayer.layer).find("div[data-coords='" + coords + "']");
        //Si encontre un resultado puedo eliminarlo
        if (div.length != 0) {
            //Elimino el tile de la capa
            div.remove();
        }
    };

    //Dibujamos la grilla del mapa en base a una imagen en base64
    //que representa una celda vacia con bordes marcados
    Canvas.updateGrid = function() {
        //Construimos la imagen de una celda vacia
        var grid = document.createElement("canvas");
        var bfr = grid.getContext("2d");

        grid.width = tw;
        grid.height = th;
        bfr.fillStyle = "rgba(0, 0, 0, 0.1)";
        bfr.fillRect(0, th - 1, tw, 1);
        bfr.fillRect(tw - 1, 0, 1, th);

        $("#canvas").css("backgroundImage", "url(" + grid.toDataURL() + ")");
        $("#canvas").find(".cursor").css({
            width: tw,
            height: th
        });
    };

    //Calcula la posicion del canvas para que este se encuentre centrado
    Canvas.updatePosition = function() {
        var top = $(window).height() / 2 + $("header").height() / 2 - $("#canvas").height() / 2;
        var left = $("#canvas_wrapper").width() / 2 + $("#editor").width() - $("#canvas").width() / 2;
        $("#canvas").css({ top: top, left: left });
    };

    return Canvas;
});