<?php
use Illuminate\Support\Facades\DB;

URL::forceRootUrl(env('APP_URL'));

Route::get('/mapa_publico', function (){
    $censos = App\Censo::where('status', App\Censo::APROBADO)->get();

    $especies = DB::table('especies')
        ->select('especies.id', 'especies.nombre', DB::raw('CONCAT(especies.nombre, " (",COUNT(especies.id), ")") AS especie'))
        ->join('censos','censos.especie_id','=','especies.id')
        ->where('censos.status', \App\Censo::APROBADO)
        ->groupBy('especies.id', 'especies.nombre')
        ->orderBy('nombre')
        ->pluck('especie', 'id');

    $calles = DB::table('calles')
        ->select('calles.id', 'calles.nombre', DB::raw('CONCAT(calles.nombre, " (",COUNT(calles.id), ")") AS calle'))
        ->join('censos','censos.calle_id','=','calles.id')
        ->where('calles.localidad_id', 1)
        ->where('censos.status', \App\Censo::APROBADO)
        ->groupBy('calles.id', 'calles.nombre')
        ->orderBy('nombre')
        ->pluck('calle', 'id');


    return view('mapa.publico', compact('censos', 'especies', 'calles'));
});

Route::get('/calles_c', function (){

    $calles = DB::table('calles')
        ->select('calles.id', 'calles.nombre')
        ->leftJoin('censos','censos.calle_id','=','calles.id')
        ->whereNotNull('censos.id')
        ->orderBy('notes.created_at', 'desc')
        ->get();

    return $calles->groupBy('id');
});

Route::get('/calles_c1', function (){

    $calles = DB::table('calles')
        ->select('calles.id', 'calles.nombre', DB::raw('CONCAT(calles.nombre, " (",COUNT(calles.id), ")") AS calle'))
        ->join('censos','censos.calle_id','=','calles.id')
        ->where('calles.localidad_id', 1)
        ->where('censos.status', \App\Censo::APROBADO)
        ->groupBy('calles.id', 'calles.nombre')
        ->orderBy('nombre')
        ->pluck('calle', 'id');

    return $calles;
});

Route::get('/', 'HomeController@index')->name('index');

Route::resource('especies', 'EspeciesController');
Route::resource('calles', 'CallesController');
Route::resource('imagenes', 'ImagenesController');
Route::resource('censos', 'CensosController');

Route::put('censos/{censo}/aprobar', 'CensosController@aprobar');
Route::delete('censos/imagen/{censo}', 'CensosController@borrarImagen');

Route::get('mapa', function(){
	$censos = App\Censo::where('status', App\Censo::APROBADO)->get();

    $especies = DB::table('especies')
        ->select('especies.id', 'especies.nombre')
        ->join('censos','censos.especie_id','=','especies.id')
        ->where('censos.status', \App\Censo::APROBADO)
        ->groupBy('especies.id', 'especies.nombre')
        ->orderBy('nombre')
        ->pluck('nombre', 'id');

    $calles = DB::table('calles')
        ->select('calles.id', 'calles.nombre')
        ->join('censos','censos.calle_id','=','calles.id')
        ->where('calles.localidad_id', 1)
        ->where('censos.status', \App\Censo::APROBADO)
        ->groupBy('calles.id', 'calles.nombre')
        ->orderBy('nombre')
        ->pluck('nombre', 'id');

	$estados = App\Acme\Constantes::getEstados();
	$tamanios = App\Acme\Constantes::getTamanios();

	return view('mapa.index2', compact('censos', 'especies', 'estados', 'tamanios', 'calles'));
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::post('logout', 'Auth\LoginController@logout');

// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/email', 'Auth\ResetPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('password/change', 'Auth\ChangePasswordController@index');
Route::post('password/change', 'Auth\ChangePasswordController@change');

Route::get('usuarios', 'UsuariosController@index');
Route::get('usuarios/{usuario}', 'UsuariosController@show');


Route::get('/reportes', function(){

    $reportes = \App\AndroidLog::orderBy('created_at', 'DESC')->paginate(2);

    return view('reportes', compact('reportes'));
})->middleware('auth');