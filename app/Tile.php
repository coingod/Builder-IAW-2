<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'tileId';
    public $timestamps=false;

    protected $fillable = ['idCategoria', 'layerId', 'cx', 'cy', 'tileInCategoria'];
}
