<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Setupbuilder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {


      //Canvas
      Schema::create('canvas', function (Blueprint $table) {
        $table->increments('canvasId');
        $table->string('descripcion')->nullable();
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
          $table->string('name')->nullable();
          $table->string('path');
          $table->string('icon');
          $table->integer('width');
          $table->integer('height');
          $table->integer('emptyTiles');
          $table->integer('habilitado');
          $table->integer('tilesetId')->unsigned();
          $table->foreign('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
      });




      //Mapa
      Schema::create('mapas', function (Blueprint $table) {
          $table->increments('mapaId');
          $table->string('token')->nullable();
          $table->string('link')->nullable();
          $table->string('descripcion')->nullable();
          $table->string('nombre')->nullable();
          $table->integer('userId')->unsigned();
          $table->integer('tilesetId')->unsigned();
          $table->integer('canvasId')->unsigned();
          $table->foreign('userId')->references('id')->on('users');
          $table->foreign('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
          $table->foreign('canvasId')->references('canvasId')->on('canvas')->onDelete('cascade');
      });


      //layers
      Schema::create('layers', function (Blueprint $table) {
          $table->increments('layerId');
          $table->string('nombre')->nullable();
          $table->string('visible')->nullable();
          $table->integer('tilesetId')->unsigned();
          $table->integer('mapaId')->unsigned();
          $table->foreign('tilesetId')->references('tilesetId')->on('tilesets')->onDelete('cascade');
          $table->foreign('mapaId')->references('mapaId')->on('mapas')->onDelete('cascade');
      });

      //Tiles
      Schema::create('tiles', function (Blueprint $table) {
          $table->increments('tileId');
          $table->integer('idCategoria');
          $table->integer('layerId')->unsigned();
          $table->foreign('layerId')->references('layerId')->on('layers')->onDelete('cascade');
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
        //
        Schema::dropIfExists('tiles');
        Schema::dropIfExists('layers');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('mapas');

        Schema::dropIfExists('canvas');
        Schema::dropIfExists('tilesets');


    }
}
