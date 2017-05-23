define([
    "jquery-ui"
], function($) {

    var CanvasTypes = {},
        Editor;
    var lastLayerID = 0;
    var scrollPaneApi, layerABorrar;

    //Iconos
    var icon_edit = "mode_edit";
    var icon_remove = "delete";
    var icon_not_remove = "delete_forever";

    CanvasTypes.initialize = function(editor) {

        Editor = editor;

        //Registramos el oyente de capa activa
        $(".canvaslist").on("mousedown", "a", function(e) {
            //Si hicimos click en el icono de visibilidad o eliminar, retornamos
            if ($(e.target).hasClass("secondary-content")) return;
            //Desmarcamos la capa actual
            $(".canvaslist a").removeClass("active");
            //Marcamos la capa como la actual
            $(e.currentTarget).addClass("active");
        });

        //Registramos los oyentes para agregar/eliminar/toggle capas
        $(".canvaslist").on("click", "a i", function(event) {
            if ($(event.target).hasClass(icon_edit)) {
                //Layers.toggle(event);
            } else if ($(event.target).text() == icon_remove) {
                //Obtenemos la capa que registro el evento
                layerABorrar = $(event.target).parent();
                $("#canvas_delete").modal("open");
            }
        });
        //Oyente de confirmacion de borrado
        $("#si_borrar_canvas").on("click", function(event) {
            CanvasTypes.deleteType(layerABorrar);
        });
        $("#canvas_add").on("click", function() {
            $("#dialog_add_canvas").modal("open");
        });

        //Configuramos el alto de la lista de capas en funcion del alto de la pagina
        $(".canvaslist").css({
            height: $(window).height() / 6
        });

        //Agregamos la barra de desplazamiento vertical al contenedor de tilesets
        scrollPaneApi = $(".canvaslist").jScrollPane().data('jsp');

        //Agregamos las capas por defecto
        this.loadCanvasTypes();

        return this;
    };

    CanvasTypes.loadCanvasTypes= function() {

        //Solicitamos al servidor todos los mapas del Administrador
        $.ajax({ method: "GET", url: "/canvas", success: function(canvasList){
            var i, map_id, map_name, map_info, map_img_path, map_token;
            //console.log(maps);
            for (i = 0; i < canvasList.length; i++) { 
                //Los canvas que el Admin marco como desabilitados no se toman en cuenta
                if(canvasList[i].habilitado == 0) 
                    return;
                //Obtenemos los datos
                canvas_id = canvasList[i].canvasId;
                canvas_name = canvasList[i].descripcion;
                canvas_row = canvasList[i].height;
                canvas_col = canvasList[i].width;
                //console.log(map_id + " " + map_name + " " + map_img_path + " " + map_token);
                CanvasTypes.addType(canvas_id, canvas_name, canvas_row, canvas_col);
            }       
        }});
    };

    CanvasTypes.crearDialog = function() {
        $("#dialog_add_canvas").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                    var nombre = $("#canvas_name").val();
                    var columnas = $("#canvas_col").val();
                    var filas = $("#canvas_row").val();

                    var canvas = {
                        "tw":64,
                        "th":64,
                        "width":columnas,
                        "height":filas,
                        "descripcion":nombre
                    };
                    //Enviamos la informacion al servidor para almacenar el canvas
                    //console.log(Editor.currentState.json);
                    $.ajax({ method: "POST", url: "/addcanvas", data:canvas, success: function(response){
                        //console.log(response);
                        CanvasTypes.resetLists();
                    }});
                    CanvasTypes.addType(nombre, filas, columnas, true);
                } // Callback for Modal close
        });
        $("#canvas_delete").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };

    //Borra el contenido de la interfaz grafica
    CanvasTypes.resetLists = function() {
        //Recargamos la lista para mostrar el nuevo canvas
        $(".canvaslist a").remove();
        //Cargamos la lista de canvas
        CanvasTypes.loadCanvasTypes();
    };

    CanvasTypes.addType = function(id, name, rows, cols) {

        //Desmarcamos la capa actual
        $(".canvaslist a").removeClass("active");
        //Si pasamos un nombre lo usamos, sino asignamos uno por defecto
        if (!name) name = "Type " + id;
        //Creamos el item con la ID correspondiente
        //var visibility = ((visible != icon_visible) ? icon_not_visible : icon_visible);
        //var canvas = $("<a href='#!' class='collection-item active' canvas-id=" + id + " > <i class='layer-name'>" + name + ": " + rows + "x" + cols + "</i> <i class='secondary-content delete material-icons'>delete</i><i class='secondary-content edit material-icons'>" + icon_edit + "</i></a>");
        var canvas = $("<a href='#!' class='collection-item active' canvas-id=" + id + " > <i class='layer-name'>" + name + ": " + rows + "x" + cols + "</i> <i class='secondary-content delete material-icons'>delete</i></a>");
        //Agregamos el item a la interfaz
        scrollPaneApi.getContentPane().append(canvas);
        //Ajustamos el scroll
        scrollPaneApi.reinitialise();

        //Si antes habia una sola capa ya es posible eliminar
        if ($(".canvaslist a").length == 2) {
            $(".canvaslist a .secondary-content").filter(".delete").text(icon_remove);
        }
    };

    CanvasTypes.deleteType = function(item) {
        
        //Obtenemos la id del canvas
        var canvas_id = $(item).attr("canvas-id");
        //console.log(canvas_id);
        $.ajax({ method: "GET", url: "/deactivatecanvas/" + canvas_id, success: function(response){
            console.log(response);
            //Eliminamos de la interfaz
            //Chequeamos si es la capa actual
            var activa = $(item).hasClass("active");
            //Eliminamos la capa de la interfaz
            item.remove();
            //Ajustamos el scroll
            scrollPaneApi.reinitialise();

            //Si era la capa actual
            if (activa) {
                //Marcamos la ultima capa como activa
                $(".canvaslist a").last().addClass("active");
            }

            //Si solo queda una capa desabilitar eliminar
            if ($(".canvaslist a").length == 1) {
                $(".canvaslist a .secondary-content").filter(".delete").text(icon_not_remove);
            }
        }});  
        
    };

    return CanvasTypes;
});