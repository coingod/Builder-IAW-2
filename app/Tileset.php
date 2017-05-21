<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tileset extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'tilesetId';
    public $timestamps=false;

    protected $fillable = ['tw', 'th'];
}
