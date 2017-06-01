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
//use App\Auth;
use Illuminate\Support\Facades\Auth;

class MapsController extends Controller
{
    //

    public function showMapsByUserId($id){
      $maps = Mapa::where('userId','=',$id)->get()->toArray();
      for($i=0; $i<count($maps) ; $i++){
        //$maps[$i]["canvasInfo"]=Canvas::where('canvasId','=',$maps[$i]["canvasId"])->first();
        $maps[$i]["tilesetInfo"]=Tileset::all()->where('tilesetId','=',$maps[$i]["tilesetId"])->first();
        $maps[$i]["layersInfo"]=Layer::where('mapaId','=',$maps[$i]["mapaId"])->get()->toArray();
        $maps[$i]["tilesetInfo"]["categories"]=Categoria::where('tilesetId','=',$maps[$i]["tilesetId"])->get()->toArray();
        for($j=0; $j<count($maps[$i]["layersInfo"]); $j++){
          $maps[$i]["layersInfo"][$j]["listaTiles"]=Tile::where('layerId','=',$maps[$i]["layersInfo"][$j]["layerId"])->get()->toArray();
        }
      }
      return compact('maps');
    }

    public function mapByToken($token){
      $arraycheck=Mapa::where('token','=',$token)->get()->toArray();
      if(count($arraycheck)>0){
        $toReturn=$arraycheck[0];
        //$toReturn["canvasInfo"]=Canvas::where('canvasId','=',$toReturn["canvasId"])->first();
        $toReturn["tilesetInfo"]=Tileset::all()->where('tilesetId','=',$toReturn["tilesetId"])->first();
        $toReturn["layersInfo"]=Layer::where('mapaId','=',$toReturn["mapaId"])->get()->toArray();
        $toReturn["tilesetInfo"]["categories"]=Categoria::where('tilesetId','=',$toReturn["tilesetId"])->get()->toArray();
        for($j=0; $j<count($toReturn["layersInfo"]); $j++){
          $toReturn["layersInfo"][$j]["listaTiles"]=Tile::where('layerId','=',$toReturn["layersInfo"][$j]["layerId"])->get()->toArray();
        }
      }

      //return view('builder', ["map" => $toReturn]);
      return compact('toReturn');
    }

    public function mapById($id){
      $arraycheck=Mapa::where('mapaId','=',$id)->get()->toArray();
      if(count($arraycheck)>0){
        $toReturn=$arraycheck[0];
        //$toReturn["canvasInfo"]=Canvas::where('canvasId','=',$toReturn["canvasId"])->first();
        $toReturn["tilesetInfo"]=Tileset::all()->where('tilesetId','=',$toReturn["tilesetId"])->first();
        $toReturn["layersInfo"]=Layer::where('mapaId','=',$toReturn["mapaId"])->get()->toArray();
        $toReturn["tilesetInfo"]["categories"]=Categoria::where('tilesetId','=',$toReturn["tilesetId"])->get()->toArray();
        for($j=0; $j<count($toReturn["layersInfo"]); $j++){
            $toReturn["layersInfo"][$j]["listaTiles"]=Tile::where('layerId','=',$toReturn["layersInfo"][$j]["layerId"])->get()->toArray();
          }
      }
      return compact('toReturn');
    }

    public function deleteMap($id){
      Mapa::where('mapaId','=',$id)->getQuery()->delete();
      return Response('Hello World', 200)->header('Content-Type', 'text/plain');
    }

    //CANVAS CONTROLLERS
    /*
    public function getCanvas(){
      return Canvas::all();
    }
    public function addCanvas(Request $request){
      $canvas=new Canvas();
      $canvas->tw=$request->tw;
      $canvas->th=$request->th;
      $canvas->width=$request->width;
      $canvas->height=$request->height;
      $canvas->descripcion=$request->descripcion;
      $canvas->habilitado=1;
      $canvas->save();
    }

    public function deactivateCanvas($id){
      $canvas= Canvas::where('canvasId','=',$id)->first();
      $canvas->habilitado=0;
      $canvas->save();
      return Response($canvas, 200)->header('Content-Type', 'text/plain');
    }
    */

    //CATEGORIAS Controllers
    public function getCategories(){
      return Categoria::all();
    }
    public function addCategory(Request $request){
      $categoria=new Categoria();
      $categoria->name=$request->name;
      $categoria->path=$request->path;
      $categoria->icon=$request->icon;
      $categoria->height=$request->height;
      $categoria->width=$request->width;
      $categoria->habilitado=1; //Por default activamos la categoría.
      $categoria->emptyTiles=$request->emptyTiles;
      $categoria->tilesetId=Tileset::first()->getKey(); //Hardcoded porque solo tenemos un tileset.
      $categoria->save();
    }

    public function deactivateCategory($id){
      $tiles=Tile::where('idCategoria', '=', $id)->get()->toArray();
      if(count($tiles)==0){
        Categoria::where('categoriaId','=',$id)->getQuery()->delete();
        return Response("eliminado", 200)->header('Content-Type', 'text/plain');
      }
      else
      {
        return Response("fail", 200)->header('Content-Type', 'text/plain');
      }

    }

    //Creacion de nuevo mapa

    public function saveMap(Request $request){
       //Lo separamos para trabajar mejor
        //Es importante cargar las cosas en orden por las llaves foraneas..
        $tilesetInfo=$request->tilesetInfo;
        $canvasInfo=$request->canvasInfo;
        $layersInfo=$request->layersInfo;
        $nombre=$request->nombre;
        $descripcion=$request->descripcion;
        //estas variables declaradas antes deben accederse como arreglo asociativo

        //Creacion de mapa
        $mapa = new Mapa();

        $mapa->width =  $canvasInfo["width"];
        $mapa->height =  $canvasInfo["height"];
        $mapa->userId =Auth::id();//Auth::user()->id;
        $mapa->tilesetId = Tileset::first()->getKey(); //Hardcoded, el tileset no cambia.
        $mapa->nombre = $nombre;
        $mapa->descripcion = $descripcion;
        $mapa->save();

        $mapa_id = $mapa->getKey(); //Esta variable se retorna, para que al generar el preview sepa la id del mapa!
        $mapa->token = substr(md5($mapa_id), 0, 32);
        $mapa->link = '/img/preview/' . $mapa_id . '.png';
        $mapa->save();

        //Buscamos ID de la primer categoría
        //$primerCategoria=Categoria::first()->categoriaId;
        //Cargamos Tiles y Layers!
        for($i=0; $i<count($layersInfo); $i++){
          $layer=new Layer();
          $layer->nombre=$layersInfo[$i]["nombre"];
          $layer->visible=$layersInfo[$i]["visible"];
          $layer->tilesetId=$mapa->tilesetId;
          $layer->mapaId=$mapa_id;
          $cantTiles=$layersInfo[$i]["cantTiles"];
          $layer->save();


          //Para dada layer recorremos todos los tiles!
          for($j=0; $j<$cantTiles; $j++){
            $tile=new Tile();
            $tile->idCategoria=$layersInfo[$i]["listaTiles"][$j]["idCategoria"];
            $tile->tileInCategoria=$layersInfo[$i]["listaTiles"][$j]["tileInCategoria"];
            $tile->cx=$layersInfo[$i]["listaTiles"][$j]["cx"];
            $tile->cy=$layersInfo[$i]["listaTiles"][$j]["cy"];
            $tile->layerId=$layer->layerId;

            $tile->save();

          };


        };
        //return Response('Hello World', 200)->header('Content-Type', 'text/plain');
        //REtornamos la ID del mapa!
        return compact('mapa_id');
    }

    //RESET DB. peligroso!!! Limpia toda la base de datos.
    public function resetDB(Request $request){
          //Borramos todas las tablas que vamos a afectar con el reset
          Tileset::getQuery()->delete();
          Categoria::getQuery()->delete();
          //Canvas::getQuery()->delete();

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
            $categoria->tilesetId=$tileset->getKey();
            $categoria->habilitado=1; //Por default activamos la categoría.
            $categoria->save();
          };
          /*
          //Canvas default big
          $canvas= new Canvas();
          $canvas->tw=$canvasInfo["tw"];
          $canvas->th=$canvasInfo["th"];
          $canvas->width=$canvasInfo["width"];
          $canvas->height=$canvasInfo["height"];
          $canvas->descripcion="Big";
          $canvas->habilitado=1;
          $canvas->save();
          //Canvas default medium
          $canvas= new Canvas();
          $canvas->tw=$canvasInfo["tw"];
          $canvas->th=$canvasInfo["th"];
          $canvas->width=9;
          $canvas->height=6;
          $canvas->descripcion="Medium";
          $canvas->habilitado=1;
          $canvas->save();
          //Canvas default small
          $canvas= new Canvas();
          $canvas->tw=$canvasInfo["tw"];
          $canvas->th=$canvasInfo["th"];
          $canvas->width=4;
          $canvas->height=4;
          $canvas->descripcion="Small";
          $canvas->habilitado=1;
          $canvas->save();
          */

          return Response('Hello World', 200)->header('Content-Type', 'text/plain');
        }

}
