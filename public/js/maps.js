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
    var umap, dmap;

    Maps.initialize = function(editor) {

        Editor = editor;

        //Obtenemos los templates para la ui de los mapas
        umap = $("#umap").removeAttr('id');
        dmap = $("#dmap").removeAttr('id');

        //Registramos el oyente de la lista de mapas
        $(".maplist").on("mousedown", "a" ,function(e) {
            //console.log(e.target);
            //console.log(e.currentTarget);
            //Si hicimos click en el icono de visibilidad o eliminar, retornamos
            if ($(e.target).hasClass("secondary-content")) return;
            //Marcamos la capa como la actual
            //console.log("MapID: " + $(e.currentTarget).attr("map-id"));
        });

        //Registramos los oyentes para editar/exportar/compartir/borrar mapas
        $(".maplist").on("click", "a i", function(event) {
            if ($(event.target).hasClass(icon_edit)) {
                //Maps.edit(event);
            } else if ($(event.target).hasClass(icon_export)) {
                //Maps.export(event);
            } else if ($(event.target).hasClass(icon_share)) {
                //Maps.share(event);
                var mapa = $(event.target).parent();
                console.log("http://localhost:8000/share/" + $(mapa).attr("token"));
            } else if ($(event.target).hasClass(icon_remove)) {
                //Obtenemos el mapa que registro el evento
                mapaAborrar = $(event.target).parent();
                $("#map_delete").modal("open");
            }
        });
        
        //Oyente de confirmacion de borrado
        $("#si_borrar_mapa").on("click", function(event) {
            Maps.deleteMap(mapaAborrar);
        });

        //Cargamos la lista de mapas predefinidos
        Maps.loadDefaultMaps();

        //Cargamos la lista de mapas del usuario
        Maps.loadUserMaps();

        return this;
    };
    
    Maps.crearDialog = function() {
        $("#maps_library").modal();
        $('#maps_library modal-content row col tabs').tabs();
        /*
        $("#dialog_new_layer").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                    var name = $("#layer_name").val();
                    Maps.addLayer(name, true);
                } // Callback for Modal close
        });
        */
        $("#map_delete").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };

    Maps.loadDefaultMaps = function() {

        //Solicitamos al servidor todos los mapas del Administrador
        $.ajax({ method: "GET", url: "/maps/1", success: function(response){
            var maps = response.maps;
            var i, map_id, map_name, map_info, map_img_path, map_token;
            //console.log(maps);
            for (i = 0; i < maps.length; i++) { 
                map_id = maps[i].mapaId;
                map_name = maps[i].nombre;
                map_img_path = maps[i].link;
                map_token = maps[i].token;
                map_info = maps[i].descripcion;
                //console.log(map_id + " " + map_name + " " + map_img_path + " " + map_token);
                Maps.addMap(map_id, map_name, map_info, map_img_path, map_token, default_maps);
            }       
        }});
    };
    
    Maps.loadUserMaps = function() {

        //Solicitamos el id del usuario
        $.ajax({ method: "GET", url: "/userid", success: function(response){

            var user_id = response.user;
            //console.log(user_id);

            //Solicitamos al servidor todos los mapas del usuario
            $.ajax({ method: "GET", url: "/maps/" + user_id, success: function(response2){

                var maps = response2.maps;
                var i, map_id, map_name, map_info, map_img_path, map_token;
                //console.log(maps);
                for (i = 0; i < maps.length; i++) { 
                    map_id = maps[i].mapaId;
                    map_name = maps[i].nombre;
                    map_img_path = maps[i].link;
                    map_token = maps[i].token;
                    map_info = maps[i].descripcion;
                    //console.log(map_id + " " + map_name + " " + map_img_path + " " + map_token);
                    Maps.addMap(map_id, map_name, map_info, map_img_path, map_token, user_maps);
                }       
            }});
        }});
    };

    //Agrega una nueva capa
    Maps.addMap = function(id, name, info, thumbnail, token, lista) {

        //Si pasamos un nombre lo usamos, sino asignamos uno por defecto
        if (!name) name = "Mapa " + currentMap;

        var thumbnail = "/img/preview/example.png";

        //Creamos el item correspondiente a la lista de mapas de usuario o de predefinidos
        //Esto depende de si estamos agregando el item a una lista o la otra
        var map = ((lista == user_maps) ? umap.clone() : dmap.clone());

        //Asignamos el id
        map.attr("map-id", id);
        //Asignamos el token
        map.attr("token", token);
        //Asignamos el nombre
        map.children().filter(".title").text(name);
        //Asignamos la descripcion
        map.children().filter("p").text(info);
        //Asignamos el preview
        map.children().filter("img").attr("src", thumbnail);

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
        map.remove();
    };

    return Maps;
});