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
use App\Auth;

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
        //Buscamos todas las layers que pertenecen al  mapa
        $maps[$i]->layersInfo = Layer::all()->where('mapaId', '=', $maps[$i]->mapaId);
        //Buscamos todas las categorias del tileset
        $maps[$i]->tilesetInfo[0]->categories = Categoria::all()->where('tilesetId', '=', $maps[$i]->tilesetId);
        //Buscamos todos los tiles que pertenezcan a cada layer utilizada en el mapa
        for($j=0; $j<count($maps[$i]->layersInfo); $j++){
          $maps[$i]->layersInfo[$j]->listaTiles=Tile::all()->where('layerId','=', $maps[$i]->layersInfo[$j]->layerId);
        }
      }

      return compact('maps');
    }

    public function canvas(){
      return Canvas::all();
    }

    public function mapByToken($token){
      $toReturn=Mapa::all()->where('token','=',$token);
      return compact('toReturn');
    }

    public function deleteMap($id){
      Mapa::where('mapaId','=',$id)->getQuery()->delete();
      return Response('Hello World', 200)->header('Content-Type', 'text/plain');
    }

    public function resetDB(Request $request){
      //Borramos todas las tablas que vamos a afectar con el reset
      Tileset::getQuery()->delete();
      Categoria::getQuery()->delete();
      Canvas::getQuery()->delete();

      //Upload de la BD inicial.. Establecemos categorias, tilesets, canvas.. cosas que quedan fijas siempre.
      $tilesetInfo=$request->tilesetInfo;
      $canvasInfo=$request->canvasInfo;

      $tileset=new Tileset();
      $tileset->tw=$tilesetInfo["tw"];
      $tileset->th=$tilesetInfo["th"];
      $tileset->save();

      for($i=0; $i<count($tilesetInfo["categories"]); $i++){
        $categoria=new Categoria();
        //    protected $fillable = ['name', 'path', 'tilesetId', 'width', 'height', 'emptyTiles'];
        $categoria->name=$tilesetInfo["categories"][$i]["name"];
        $categoria->path=$tilesetInfo["categories"][$i]["path"];
        $categoria->width=$tilesetInfo["categories"][$i]["width"];
        $categoria->height=$tilesetInfo["categories"][$i]["height"];
        $categoria->emptyTiles=$tilesetInfo["categories"][$i]["emptyTiles"];
        $categoria->icon=$tilesetInfo["categories"][$i]["icon"];
        $categoria->tilesetId=$tileset->tilesetId;
        $categoria->save();
      };

      $canvas= new Canvas();
      $canvas->tw=$canvasInfo["tw"];
      $canvas->th=$canvasInfo["th"];
      $canvas->width=$canvasInfo["width"];
      $canvas->height=$canvasInfo["height"];
      $canvas->save();

      return Response('Hello World', 200)->header('Content-Type', 'text/plain');

    }

    public function store(Request $request){
       //Lo separamos para trabajar mejor
        //Es importante cargar las cosas en orden por las llaves foraneas..
        $tilesetInfo=$request->tilesetInfo;
        $canvasInfo=$request->canvasInfo;
        $layersInfo=$request->layersInfo;
        //estas variables declaradas antes deben accederse como arreglo asociativo

        //Creacion de mapa
        $mapa = new Mapa();

        //Buscamos el canvas en la BD que se corresponda con el que viene en el json.. No deberia haber repetidos!
        $consultaCanvas = Canvas::all()->where("height","=",$canvasInfo["height"])->where("width","=",$canvasInfo["width"])->first();
        $canvasid=$consultaCanvas->canvasId;

        $mapa->canvasId = $canvasid;
        $mapa->userId = 1; //App\Auth::user()->getKey();
        $mapa->tilesetId = Tileset::first()->tilesetId; //Hardcoded, el tileset no cambia.

        $mapa->save();

        //Buscamos ID de la primer categoría
        $primerCategoria=Categoria::first()->categoriaId;
        //Cargamos Tiles y Layers!
        for($i=0; $i<count($layersInfo); $i++){
          $layer=new Layer();
          $layer->name=$layersInfo[$i]["nombre"];
          $layer->visible=$layersInfo[$i]["visible"];
          $layer->tilesetId=$mapa->tilesetId;
          $layer->mapaId=$mapa->getKey();
          $cantTiles=$layersInfo[$i]["cantTiles"];
          $layer->save();


          //Para dada layer recorremos todos los tiles!
          for($j=0; $j<$cantTiles; $j++){
            $tile=new Tile();
            //el campo idCategoria en el json tiene el offset dentro de las categorias.
            //Sumamos a la primera y obtenemos el id real en la DB!
            $tile->categoriaId=$layersInfo[$i]["listaTiles"][$j]["idCategoria"]+$primerCategoria;
            $tile->tileInCategoria=$layersInfo[$i]["listaTiles"][$j]["tileInCategoria"];
            $tile->coordX=$layersInfo[$i]["listaTiles"][$j]["cx"];
            $tile->coordY=$layersInfo[$i]["listaTiles"][$j]["cy"];
            $tile->layerId=$layer->layerId;

            $tile->save();

          };


        };
        return Response('Hello World', 200)->header('Content-Type', 'text/plain');
    }

    public function publish(){
      $toReturn=Tile::all();
      return compact('toReturn');
    }
}
