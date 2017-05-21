define([
    "jquery-ui"
], function($) {

    var Maps = {},
        Editor;

    var currentMap = 0;
    var mapaAborrar;

    //Iconos
    var icon_share = "share";
    var icon_export = "file_download";
    var icon_remove = "delete";
    var icon_edit = "mode_edit";

    var default_maps = "default_maps";
    var user_maps = "user_maps";

    Maps.initialize = function(editor) {

        Editor = editor;

        //Registramos el oyente de la lista de mapas
        $(".maplist").on("mousedown", "a" ,function(e) {
            //console.log(e.target);
            //console.log(e.currentTarget);
            //Si hicimos click en el icono de visibilidad o eliminar, retornamos
            if ($(e.target).hasClass("secondary-content")) return;
            //Marcamos la capa como la actual
            console.log("MapID: " + $(e.currentTarget).attr("map-id"));
        });

        //Registramos los oyentes para editar/exportar/compartir/borrar mapas
        $(".maplist").on("click", "a i", function(event) {
            if ($(event.target).hasClass(icon_edit)) {
                //Maps.edit(event);
            } else if ($(event.target).hasClass(icon_export)) {
                //Maps.export(event);
            } else if ($(event.target).hasClass(icon_share)) {
                //Maps.share(event);
            } else if ($(event.target).hasClass(icon_remove)) {
                //Obtenemos el mapa que registro el evento
                mapaAborrar = $(event.target).parent();
                $("#layer_delete").modal("open");
            }
        });
        /*
        //Oyente de confirmacion de borrado
        $("#si").on("click", function(event) {
            Maps.deleteLayer(mapaAborrar);
        });
        */

        Maps.addMap(null, default_maps);
        Maps.addMap(null, default_maps);
        Maps.addMap(null, default_maps);

        Maps.addMap("Mi Mapa Personalizado", user_maps);

        return this;
    };
    /*
    Maps.crearDialog = function() {
        $("#dialog_new_layer").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                    var name = $("#layer_name").val();
                    Maps.addLayer(name, true);
                } // Callback for Modal close
        });
        $("#layer_delete").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };
    */
    

    //Agrega una nueva capa
    Maps.addMap = function(name, lista) {

        //Si pasamos un nombre lo usamos, sino asignamos uno por defecto
        if (!name) name = "Mapa " + currentMap;

        var thumbail = "/img/preview/example.png";
        var delete_span = "<i class='secondary-content "+icon_remove+" material-icons'>" + icon_remove + "</i>";
        var share_span = "<i class='secondary-content "+icon_share+" material-icons'>" + icon_share +"</i>";
        var export_span = "<i class='secondary-content "+icon_export+" material-icons'>" + icon_export + "</i>";
        var edit_span = "<i class='secondary-content "+icon_edit+" material-icons'>" + icon_edit + "</i>";
        var map_id = "map-id=" + currentMap++;

        //Creamos el item con la ID correspondiente
        //var map = $("<a href='#!' class='collection-item active' data-id=" + currentMap + " > <i class='layer-name'>" + name + "</i> <i class='secondary-content delete material-icons'>delete</i><i class='secondary-content visibility material-icons'>" + icon_visible + "</i></a>");
        var map;

        if(lista == default_maps)
            map = $("<a href='#!' class='collection-item avatar' " + map_id + "><img src='" + thumbail + "' class='circle'><i class='title'>" + name + "</i><p>First Line <br>Second Line</p>" + share_span + export_span + "</a>");
        else if (lista == user_maps)
            map = $("<a href='#!' class='collection-item avatar' " + map_id + "><img src='" + thumbail + "' class='circle'><i class='title'>" + name + "</i><p>First Line <br>Second Line</p>" + delete_span + share_span + export_span + edit_span + "</a>");

        //Agregamos el item a la lista de mapas precargados
        $("#"+lista+" .maplist").append(map);
    };

    Maps.removeAll = function() {
        //Borramos TODAS las capas
        $(".maplist").each(function() {
            Maps.deleteMap($(this));
        });
    };

    //Elimina la capa actualmente seleccioanda junto con su contenido
    Maps.deleteMap = function(map) {

        //Eliminamos la capa de la interfaz
        layer.remove();
    };

    return Maps;
});