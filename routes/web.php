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
	return view('builder');
    //return view('welcome');
});
//MAPAS
//Mostrar mapas de un usuario
Route::get('/maps/{id}','MapsController@showMapsByUserId');
//Map by token
Route::get('/share/{token}','MapsController@mapByToken');
//Map by mapaId
Route::get('/showmap/{id}','MapsController@mapById');
//Borrar mapa by mapaId
Route::get('/deletemap/{id}','MapsController@deleteMap');

//CANVAS
//Obtener todos los canvas
Route::get('/canvas', 'MapsController@getCanvas');
//Agregar nuevo canvas
Route::post('/addcanvas', 'MapsController@addCanvas');
//Eliminar canvas
Route::get('/deactivatecanvas/{id}', 'MapsController@deactivateCanvas');

//Guardar mapa actual
Route::post('/savemap','MapsController@saveMap');

//Reset de Database
Route::post('/resetDB', 'MapsController@resetDB');

//USERS
//Para crear los roles en la base de datos (Admin/Member/Guest)
Route::get('/setup-roles', function () {

	//Creamos al usuario admin
	//$user = App\User::where('name', '=', 'admin')->first();
	$user = new App\User();
	$user->id = 1;
	$user->name = 'admin';
	$user->email = 'admin@builder.com';
	$user->password = bcrypt('root');
	$user->save();

	//Creamos el perfil de Administrador
	$admin = new App\Role();
	$admin->name         = 'admin';
	$admin->display_name = 'Administrator'; // optional
	$admin->description  = 'El usuario puede administrar y editar mapas modelo'; // optional
	$admin->save();

	//Creamos el perfil de Usuario Registrado
	$member = new App\Role();
	$member->name         = 'member';
	$member->display_name = 'Miembro'; // optional
	$member->description  = 'El usuario puede guardar y recuperar sus mapas personalizados'; // optional
	$member->save();

	//Creamos los permisos para cada Rol
	$crearMapaModelo = new App\Permission();
	$crearMapaModelo->name         = 'create-map-model';
	$crearMapaModelo->display_name = 'Crear Mapa Modelo'; // optional
	$crearMapaModelo->description  = 'Crea un nuevo Mapa Modelo para todos los usuarios'; // optional
	$crearMapaModelo->save();

	$eliminarMapaModelo = new App\Permission();
	$eliminarMapaModelo->name         = 'delete-map-model';
	$eliminarMapaModelo->display_name = 'Eliminar Mapa Modelo'; // optional
	$eliminarMapaModelo->description  = 'Elimina un Mapa Modelo existente'; // optional
	$eliminarMapaModelo->save();

	//Adjuntamos los permisos a los Roles
	$admin->attachPermission(array($crearMapaModelo, $eliminarMapaModelo));

	//Adjuntamos el rol de Administrador
	$user->attachRole($admin); // parameter can be an Role object, array, or id

	//Obtenemos todos los Usuarios para listarlos
	$users = App\User::all();

	//return view('welcome');
    return view('users', compact('users'));
});

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
Route::post('/thumbnail', 'ThumbnailController@save');

Auth::routes();

Route::get('/readme', function(){
	return view('readme');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
