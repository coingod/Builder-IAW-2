<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //

    protected $connection = 'mysql';
    protected $primaryKey = 'categoriaId';
    public $timestamps=false;

    protected $fillable = ['name', 'path', 'tilesetId', 'width', 'height', 'emptyTiles', 'icon'];
}
