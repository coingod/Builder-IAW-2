<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mapa extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'mapId';
    public $timestamps=false;

    protected $fillable = ['token', 'userId', 'tilesetId', 'canvasId'];

}
