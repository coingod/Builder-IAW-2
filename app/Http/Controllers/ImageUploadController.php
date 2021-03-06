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

class ImageUploadController extends Controller
{
    //
    public function save(Request $request, $folder)
    {
      //$data = $request->input('data');//Input::all();//'data:image/png;base64,AAAFBfj42Pj4';
      $name = $request->id;
      $data = $request->str;
      $data = substr($data,22);
      $data = str_replace(' ', '+', $data);
      $data = base64_decode($data);
      $path = public_path() . '/img/' . $folder . '/' . $name . '.png';
      file_put_contents( $path,$data);
      return Response('Hello World', 200)->header('Content-Type', 'text/plain');
    }
}
