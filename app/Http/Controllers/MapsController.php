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
use App\User;
use App\Role;
use App\Permission;
//use Illuminate\Support\Facades\Auth;

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

    //SetupDB solo debe ejecutarse cuando estamos con la BD vacía.
    public function setupDB(Request $request){

          //Setup Tileset
          $tilesetInfo=$request->tilesetInfo;

          $tileset=new Tileset();
          $tileset->tw=64;
          $tileset->th=64;
          $tileset->save();

          //Setup admin

          $user = new User();
        	$user->id = 1;
        	$user->name = 'admin';
        	$user->email = 'admin@builder.com';
        	$user->password = bcrypt('root');
        	$user->save();

        	//Creamos el perfil de Administrador
        	$admin = new Role();
        	$admin->name         = 'admin';
        	$admin->display_name = 'Administrator'; // optional
        	$admin->description  = 'El usuario puede administrar y editar mapas modelo'; // optional
        	$admin->save();

        	//Creamos el perfil de Usuario Registrado
        	$member = new Role();
        	$member->name         = 'member';
        	$member->display_name = 'Miembro'; // optional
        	$member->description  = 'El usuario puede guardar y recuperar sus mapas personalizados'; // optional
        	$member->save();

        	//Creamos los permisos para cada Rol
        	$crearMapaModelo = new Permission();
        	$crearMapaModelo->name         = 'create-map-model';
        	$crearMapaModelo->display_name = 'Crear Mapa Modelo'; // optional
        	$crearMapaModelo->description  = 'Crea un nuevo Mapa Modelo para todos los usuarios'; // optional
        	$crearMapaModelo->save();

        	$eliminarMapaModelo = new Permission();
        	$eliminarMapaModelo->name         = 'delete-map-model';
        	$eliminarMapaModelo->display_name = 'Eliminar Mapa Modelo'; // optional
        	$eliminarMapaModelo->description  = 'Elimina un Mapa Modelo existente'; // optional
        	$eliminarMapaModelo->save();

        	//Adjuntamos los permisos a los Roles
        	$admin->attachPermission(array($crearMapaModelo, $eliminarMapaModelo));

        	//Adjuntamos el rol de Administrador
        	$user->attachRole($admin); // parameter can be an Role object, array, or id

        	//Obtenemos todos los Usuarios para listarlos
        	$users = User::all();

        	//return view('welcome');
          return view('users', compact('users'));

        }

}
