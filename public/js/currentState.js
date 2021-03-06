define([
    "jquery-ui"
], function($) {

    var currentState = {},
        Editor, tilesetInfo, layers, botonExportar;

    currentState.initialize = function(editor) {
        Editor = editor;
        tilesetInfo = Editor.Tileset.info; //Obtenemos la info del tileset actual
        layers = Editor.Layers;

        botonExportar = document.createElement('a');
        botonExportar.textContent = "Exportar JSON";
        document.getElementById("export_map").appendChild(botonExportar);

        //Inicializamos getter de archivo
        document.getElementById("file-input").addEventListener('change', currentState.readFile, false);

        document.getElementById("export_map").addEventListener('mouseenter', currentState.loadCurrentState, false);

        return currentState;
    };

    currentState.crearDialog = function() {
        $("#dialog_import").modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
    };

    currentState.loadCurrentState = function() {
        currentState.json = {}; //Esto es lo que vamos a utilizar como objeto
        currentState.json.tilesetInfo = Editor.Tileset.info;
        
        var wCanvas = parseInt($("#canvas").css("width")) / Editor.Tileset.info.tw;
        var hCanvas = parseInt($("#canvas").css("height")) / Editor.Tileset.info.th;
        currentState.json.width = wCanvas;
        currentState.json.height = hCanvas;

        currentState.loadLayerInfo();

        var jsonse = JSON.stringify(currentState.json);
        var blob = new Blob([jsonse], { type: "application/json" });
        var url = URL.createObjectURL(blob);

        botonExportar.href = url;
        botonExportar.download = "builder_map.json";
    };

    currentState.loadLayerInfo = function() {
        var listaTiles, listaAct, tileAct, infoTile, idTile, idCat, cxTile, cyTile;
        var listaCapas = $("#layerlist a");
        var jsonCapas = new Array();
        var i, j;

        for (i = 0; i < listaCapas.length; i++) {
            jsonCapas[i] = {};
            listaAct = $(listaCapas).get(i);
            jsonCapas[i].nombre = $(listaAct).children(".layer-name").text();
            jsonCapas[i].visible = $(listaAct).children(".visibility").text();
            //Obtenemos cada uno de los tiles de esta capa
            listaTiles = $("#tiles > div").get(i);
            jsonCapas[i].listaTiles = new Array(listaTiles.childElementCount);
            jsonCapas[i].cantTiles=listaTiles.childElementCount;

            for (j = 0; j < listaTiles.childElementCount; j++) {
                tileAct = $(listaTiles).children()[j];
                infoTile = $(tileAct).css("background-position");
                idCategoria = parseInt($(tileAct).attr("class").split("_")[1]);
                idTile = currentState.getId(infoTile, idCategoria);
                cxTile = parseInt($(tileAct).css("left")) / Editor.Tileset.info.tw;
                cyTile = parseInt($(tileAct).css("top")) / Editor.Tileset.info.th;
                jsonCapas[i].listaTiles[j]={};
                jsonCapas[i].listaTiles[j].tileInCategoria=idTile;
                jsonCapas[i].listaTiles[j].idCategoria=idCategoria;
                jsonCapas[i].listaTiles[j].cx=cxTile;
                jsonCapas[i].listaTiles[j].cy=cyTile;

                 // = [idTile, idCategoria, cxTile, cyTile];
                //console.log(jsonCapas[i].listaTiles[j]);
            }
        }
        currentState.json.layersInfo = jsonCapas;

    };

    currentState.importar = function(jsonString) {
        currentState.json = JSON.parse(jsonString);
        //Pedimos al Editor que cargue los datos
        Editor.loadExternal();
    };

    currentState.readFile = function(e) {

        //Cierro el cuadro de dialogo
        $("#dialog_import").modal("close");

        var stringJson = "";
        //Debemos levantar el json y ponerlo en currentState.json

        var file = e.target.files[0];
        if (!file) {
            return;
        }
        var reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function(e) {
            currentState.importar(e.target.result);
        };

    };

    currentState.getId = function(backPos, idCategoria) {
        var coords = backPos.split("px");
        var cx = Math.abs(parseInt(coords[0]) / tilesetInfo.tw);
        var cy = Math.abs(parseInt(coords[1]) / tilesetInfo.th);
        var i=0, index;
        for(i=0; i<currentState.json.tilesetInfo.categories.length; i++){
          if(currentState.json.tilesetInfo.categories[i].categoriaId==idCategoria){
            index=i;
            break;
          }
        }
        if (index>=0)
          return cx + (cy * currentState.json.tilesetInfo.categories[index].width / currentState.json.tilesetInfo.tw);
        else
          return -1;
    };

    return currentState;
});
