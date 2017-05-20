<?php

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

//Para crear los roles en la base de datos (Admin/Member/Guest)
Route::get('/setup-roles', function () {

	//Creamos al usuario admin
	//$user = App\User::where('name', '=', 'admin')->first();
	$user = new App\User();
	$user->id = 1;
	$user->name = 'admin';
	$user->email = 'admin@builder.com';
	$user->password = 'root';
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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
