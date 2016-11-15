<?php
URL::forceRootUrl('http://arboladoapp.bahiablanca.gob.ar/');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
Event::listen('auth.login', function($event)
{
    Auth::user()->last_login = new DateTime;
    Auth::user()->last_login_ip = Request::getClientIp();
    Auth::user()->save();
});
*/

Route::get('/', 'HomeController@index')->name('index');

Route::resource('especies', 'EspeciesController');
Route::resource('calles', 'CallesController');

Route::resource('censos', 'CensosController');
Route::put('censos/{id}/aprobar', 'CensosController@aprobar');
Route::delete('censos/imagen/{id}', 'CensosController@borrarImagen');

Route::get('mapa', function(){
	$arboles = App\Arbol::where('status', App\Arbol::APROBADO)->get();
	$especies = App\Especie::pluck('nombre', 'id');
	$estados = App\Acme\Constantes::getEstados();
	$tamanios = App\Acme\Constantes::getTamanios();

	return view('mapa.index2', compact('arboles', 'especies', 'estados', 'tamanios'));
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes... removed!

// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/email', 'Auth\ResetPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//Auth::routes();

Route::get('line', function(){
	$data = [
		'az' => 'r3',
		'b3' => 'w',
		's1' => 'sf'
	];
});