<?php

namespace App\Http\Controllers;

use App\Mapa;
use App\Canvas;
use App\Categoria;
use App\Layer;
use App\Tile;
use App\Tileset;


class MapsController extends Controller
{
    //
    public function show($id){
      $maps = Mapa::all()->where('userId','=',$id);

      return compact('maps');
    }

    public function save(){

    }
}
