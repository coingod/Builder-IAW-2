<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mapa extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'mapaId';
    public $timestamps=false;

    protected $fillable = ['token', 'userId', 'tilesetId', 'canvasId', 'link', 'descripcion', 'nombre'];
}
