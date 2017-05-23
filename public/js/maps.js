define([
    "jquery-ui"
], function($) {

    var Maps = {},
        Editor;

    var currentMap = 0;
    var mapaAborrar;
    var recentlySaved = false;

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
            //Cargamos el mapa seleccionado
            if(editor) {
                var map_id = $(e.currentTarget).attr("map-id");
                $.ajax({ method: "GET", url: "/showmap/" + map_id, success: function(response){
                    Editor.currentState.json = {};
                    Editor.currentState.json = response.toReturn;
                    console.log(Editor.currentState.json);
                    Editor.loadExternal();
                }});            
            }
            //console.log("MapID: " + $(e.currentTarget).attr("map-id"));
        });

        //Registramos los oyentes para editar/exportar/compartir/borrar mapas
        $(".maplist").on("click", "a i", function(event) {
            if ($(event.target).hasClass(icon_edit)) {
                //Maps.edit(event);
            } else if ($(event.target).hasClass(icon_export)) {
                /*
                //Maps.export(event);
                var map_id = $(event.target).parent().attr("map-id");
                $.ajax({ method: "GET", url: "/showmap/" + map_id, success: function(response){
                    //Editor.currentState.json = {};
                    //Editor.currentState.json = response.toReturn;
                    console.log(response);
                    //Editor.loadExternal();
                }}); 
                */

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

        /*
        $("#save_map").on("click", function(){
          Editor.currentState.loadCurrentState();
          $.ajax({ method: "POST", url: "/savemap", data:Editor.currentState.json });
        });
        */

        $("#save_map").on("click", function() {
            $("#dialog_save_map").modal("open");
        });
        
        //Oyente de confirmacion de borrado
        $("#si_borrar_mapa").on("click", function(event) {
            Maps.deleteMap(mapaAborrar);
            Maps.resetLists();
        });

        //Cargamos la lista de mapas predefinidos
        Maps.loadDefaultMaps();

        //Cargamos la lista de mapas del usuario
        Maps.loadUserMaps();

        return this;
    };
    
    Maps.crearDialog = function() {
        $("#maps_library").modal({
            ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                if(recentlySaved)
                    Maps.resetLists();
                recentlySaved = false;
              },
        });
        $('#maps_library modal-content row col tabs').tabs();
        
        $("#dialog_save_map").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
            complete: function() {
                    var name = $("#map_name").val();
                    var descripcion = $("#map_info").val();
                    //Enviamos la informacion al servidor para almacenar el mapa
                    Editor.currentState.loadCurrentState();
                    //Agregamos el nombre
                    Editor.currentState.json.nombre = name;
                    //Agregamos la descripcion
                    Editor.currentState.json.descripcion = descripcion;
                    //console.log(Editor.currentState.json);
                    $.ajax({ method: "POST", url: "/savemap", data:Editor.currentState.json, success: function(response){
                        //Generamos una imagen PNG para vista previa y la enviamos al servidor (NewWindow: false)
                        Editor.Canvas.createPNG(false, response.mapa_id);
                        //console.log(response);
                        recentlySaved = true;
                    }});
                } // Callback for Modal close
        });
        
        $("#map_delete").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };

    //Borra el contenido de la interfaz grafica
    Maps.resetLists = function() {
        //Recargamos la lista de mapas para mostrar el nuevo mapa
        $(".maplist a").remove();
        //Cargamos la lista de mapas predefinidos
        Maps.loadDefaultMaps();
        //Cargamos la lista de mapas del usuario
        Maps.loadUserMaps();
    };

    Maps.loadDefaultMaps = function() {

        //Solicitamos al servidor todos los mapas del Administrador
        $.ajax({ method: "GET", url: "/maps/1", success: function(response){
            var maps = response.maps;
            var i, map_id, map_name, map_info, map_img_path, map_token;
            var j;
                var layer_names
            //console.log(maps);
            for (i = 0; i < maps.length; i++) { 
                layer_names = "Capas: ";
                map_id = maps[i].mapaId;
                map_name = maps[i].nombre;
                map_img_path = maps[i].link;
                map_token = maps[i].token;
                map_info = maps[i].descripcion;
                for (j = 0; j < maps[i].layersInfo.length; j++) { 
                        //layer_info.push(maps[i].layersInfo[j].nombre);
                        layer_names += maps[i].layersInfo[j].nombre + " ";
                    }
                //console.log(map_id + " " + map_name + " " + map_img_path + " " + map_token);
                Maps.addMap(map_id, map_name, map_info, map_img_path, map_token, default_maps, layer_names);
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
                var j;
                var layer_names;
                //console.log(maps);
                for (i = 0; i < maps.length; i++) { 
                    layer_names = "Capas: ";
                    map_id = maps[i].mapaId;
                    map_name = maps[i].nombre;
                    map_img_path = maps[i].link;
                    map_token = maps[i].token;
                    map_info = maps[i].descripcion;
                    //console.log("Layers: "+maps[i].layersInfo.length);
                    for (j = 0; j < maps[i].layersInfo.length; j++) { 
                        //layer_info.push(maps[i].layersInfo[j].nombre);
                        layer_names += maps[i].layersInfo[j].nombre;
                    }
                    //console.log(map_id + " " + map_name + " " + map_img_path + " " + map_token);
                    Maps.addMap(map_id, map_name, map_info, map_img_path, map_token, user_maps, layer_names);
                }       
            }});
        }});
    };

    //Agrega una nueva capa
    Maps.addMap = function(id, name, info, thumbnail, token, lista, capas) {

        //Si pasamos un nombre lo usamos, sino asignamos uno por defecto
        if (!name) name = "Mapa " + currentMap;

        //var thumbnail = "/img/preview/id.png";

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
        map.children().filter("p").html(info+" <br> "+capas);
        //Asignamos el preview
        map.children().filter("img").attr("src", thumbnail);

        //Agregamos el item a la lista de mapas precargados
        $("#"+lista+" .maplist").append(map);
    };
    /*
    //Elimina TODOS los mapas del User y Predefinidos de la base de datos!
    Maps.removeAll = function() {
        //Borramos TODAS las capas
        $(".maplist").each(function() {
            Maps.deleteMap($(this));
        });
    };
    */

    //Elimina el mapa especificado de la base de datos!
    Maps.deleteMap = function(map) {
        //Obtenemos el id del mapa y enviamos una solicitud al servidor para eliminarlo de la BD
        var map_id = $(map).attr("map-id");
        $.ajax({ method: "GET", url: "/deletemap/" + map_id, success: function(response){
            console.log(response);
            //Eliminamos la capa de la interfaz
            map.remove();
        }});  
    };

    return Maps;
});