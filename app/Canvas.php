<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Canvas extends Model
{
    //
    protected $connection = 'mysql';
    protected $primaryKey = 'canvasId';
    public $timestamps=false;

    protected $fillable = ['tw', 'th', 'width', 'height', 'descripcion'];
    protected $table = 'canvas';
}
