<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuilderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      /*
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        */
        //Mapa
        Schema::create('mapas', function (Blueprint $table) {
            $table->increments('mapaId');
            $table->string('token');
            $table->string('link');
            $table->string('descripcion');
            $table->string('nombre');
            $table->integer('userId');
            $table->integer('tilesetId');
            $table->integer('canvasId');
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
            $table->foreign('canvasId')->references('canvasId')->on('canvas')->onDelete('cascade');
        });

        //Canvas
        Schema::create('canvas', function (Blueprint $table) {
          $table->increments('canvasId');
          $table->string('descripcion');
          $table->integer('tw');
          $table->integer('th');
          $table->integer('width');
          $table->integer('height');
          $table->integer('habilitado');
  });

        //Tileset
        Schema::create('tilesets', function (Blueprint $table) {
            $table->increments('tilesetId');
            $table->integer('tw');
            $table->integer('th');
        });

        //categorias
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('categoriaId');
            $table->string('name');
            $table->string('path');
            $table->string('icon');
            $table->integer('width');
            $table->integer('height');
            $table->integer('emptyTiles');
            $table->integer('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
        });

        //layers
        Schema::create('layers', function (Blueprint $table) {
            $table->increments('layerId');
            $table->string('nombre');
            $table->string('visible');
            $table->integer('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
            $table->integer('mapaId')->references('mapaId')->on('mapas')->onDelete('cascade');
        });

        //Tiles
        Schema::create('tiles', function (Blueprint $table) {
            $table->increments('tileId');
            $table->integer('idCategoria');
            $table->integer('layerId')->references('layerId')->on('layers')->onDelete('cascade');
            $table->integer('cx');
            $table->integer('cy');
            $table->integer('tileInCategoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
