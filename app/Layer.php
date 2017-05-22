<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'layerId';
    public $timestamps=false;

    protected $fillable = ['nombre', 'visible', 'tilesetId', 'mapaId'];

}
