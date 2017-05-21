<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mapa;
use App\Canvas;
use App\Categoria;
use App\Layer;
use App\Tile;
use App\Tileset;


class MapsController extends Controller
{
    //
     public  $datos='';
    public function show($id){
      $maps = Mapa::all()->where('userId','=',$id);
      for($i=0; $i<count($maps) ; $i++){
        //Buscamos el canvas que fue utilizado en el mapa
        $maps[$i]->canvas = Canvas::all()->where('canvasId','=',$maps[$i]->canvasId);
        //Buscamos el tileset que fue utilizado en el mapa
        $maps[$i]->tilesetInfo = Tileset::all()->where('tilesetId', '=', $maps[$i]->tilesetId);
        //Buscamos todas las layers que pertenecen al tileset utilizado en el mapa
        $maps[$i]->layersInfo = Layer::all()->where('tilesetId', '=', $maps[$i]->tilesetId);
        //Buscamos todas las categorias del tileset
        $maps[$i]->tilesetInfo[0]->categories = Categoria::all()->where('tilesetId', '=', $maps[$i]->tilesetId);
        //Buscamos todos los tiles que pertenezcan a cada layer utilizada en el mapa
        for($j=0; $j<count($maps[$i]->layersInfo); $j++){
          $maps[$i]->layersInfo[$j]->listaTiles=Tile::all()->where('layerId','=', $maps[$i]->layersInfo[$j]->layerId);
        }
      }

      return compact('maps');
    }

    public function store(Request $request){
        //Obtenemos array asociativo del objeto javascript que enviamos por mensaje
        $datos=stripslashes($request->data);
        $datos = json_decode($datos,true);

        //Lo separamos para trabajar mejor
        $canvasInfo = $datos["canvasInfo"];
        $tilesetInfo = $datos["tilesetInfo"];
        $layersInfo = $datos["layersInfo"];
        //$mapa Hacer

        //Es importante cargar las cosas en orden por las llaves foraneas..


        //Creacion de mapa
        $mapa = new Mapa();
        $tileset = new Tileset();

        //IDS HARDCODEADOS
        $canvasId = 1; //Hardcoded. Tomar del json
        $tilesetId = 1; //Hardcoded. Tomar del json
        $userId = 1; //Hardcoded. Tomar del json


        //$json = json_decode($request->getContent(), true);
        $categoria = new Categoria();
        $categoria->name=$layersInfo;
        $categoria->path='hola';
        $categoria->width=25;
        $categoria->height=30;
        $categoria->tilesetId=1;
        $categoria->save();
        return Response('Hello World', 200)->header('Content-Type', 'text/plain');
        echo "<script>console.log(<?= json_encode($datos); ?>);</script>";
    }

    public function publish(){
      $toReturn=Categoria::all();
      return compact('toReturn');
    }
}
