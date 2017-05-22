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

class ThumbnailController extends Controller
{
    //
    public function save(Request $request)
    {
      //$data = $request->input('data');//Input::all();//'data:image/png;base64,AAAFBfj42Pj4';
      $data = 'data:image/png;base64,AAAFBfj42Pj4';

      list($type, $data) = explode(';', $data);
      error_log("explode1");
      list(, $data)      = explode(',', $data);
      error_log("explode2");
      $data = base64_decode($data);
      error_log("base");
      $path = public_path() . '/img/preview/image.png';

      file_put_contents($path, $data);

      return Response('Hello World', 200)->header('Content-Type', 'text/plain');
    }

}
