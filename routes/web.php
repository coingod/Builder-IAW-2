<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
	return view('builder',['map'=>'new']);
    //return view('welcome');
});
//MAPAS
//Mostrar mapas de un usuario
Route::get('/maps/{id}','MapsController@showMapsByUserId');
//Map by token
Route::get('/getmap/{token}','MapsController@mapByToken');

//Map by mapaId
Route::get('/showmap/{id}','MapsController@mapById');
//Borrar mapa by mapaId
Route::get('/deletemap/{id}','MapsController@deleteMap');
/*
//CANVAS
//Obtener todos los canvas
Route::get('/canvas', 'MapsController@getCanvas');
//Agregar nuevo canvas
Route::post('/addcanvas', 'MapsController@addCanvas');
//Eliminar canvas
Route::get('/deactivatecanvas/{id}', 'MapsController@deactivateCanvas');
*/
//CATEGORIAS
//get all
Route::get('/categories', 'MapsController@getCategories');
//Agregar nuevo canvas
Route::post('/addcategory', 'MapsController@addCategory');
//Eliminar canvas
Route::get('/deactivatecategory/{id}', 'MapsController@deactivateCategory');

//Guardar mapa actual
Route::post('/savemap','MapsController@saveMap');

//USERS
//Para crear los roles en la base de datos (Admin/Member/Guest)
//Ademas sube el tileset default
Route::get('/setupdb', 'MapsController@setupDB');

Route::get('/users', function () {
	//Obtenemos todos los Usuarios para listarlos
	$users = App\User::all();
	$roles = App\Role::all();

	//return view('welcome');
    return view('users', compact('users', 'roles'));
});

//Retorna la id del usuario actual
Route::get('/userid', function () {
	//Obtenemos la id del usuario actual
	$user = -1;
	if(Auth::user())
		$user = Auth::user()->id;

	//return view('welcome');
    return compact('user');
});

//Vista previa de los mapas
//Route::post('/thumbnail', 'ThumbnailController@save');
Route::post('/upload/img/{folder}', 'ImageUploadController@save');

Auth::routes();

Route::get('/readme', function(){
	return view('readme');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

//Esto es lo que se comparte
Route::get('/{token}', function($token){
	return view('builder',['map'=>$token]);
});
