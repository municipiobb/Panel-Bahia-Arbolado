<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('reportpath', function (Request $request) {
});

Route::get('/calles/{date?}', function ($date = '') {
    $data = [];
    if ( $date ) {
        if ( \App\Calle::where('updated_at', '>', \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $date))->count() )
            $data = \App\Calle::all();
    } else {
        $data = \App\Calle::all();
    }

    return response()->json([
        'data' => $data
    ]);
});

Route::get('/especies/{date?}', 'EspeciesController@getAll');

Route::get('/getFormData', 'ApiController@getFormData');

Route::get('/censos', 'ApiController@getAll');
Route::get('/censos.php', 'ApiController@getAll');
Route::post('/censo', 'ApiController@saveCenso');
Route::post('/censo/{id}/imagenes', 'ApiController@saveImagen');

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('arboles', function (Request $request) {
    $calle = request('calle');
    $altura1 = request('altura1');
    $altura2 = request('altura2');

    $especie = request('especie');

    $censos = \App\Censo::with('especie')->orderBy('id');

    if ( $calle )
        $censos = $censos->where('calle_id', $calle);

    if ( $altura1 && $altura2 )
        $censos = $censos->wittwen($altura1, $altura2);

    if ( $especie )
        $censos = $censos->where('especie_id', $especie);

    return $censos->get();
});

Route::post('mapa_ll', function (Request $request) {
    $especie = $request->especie;
    $estado = $request->estado;
    $tamanio = $request->tamanio;
    $calle = $request->calle;

    $altura_min = $request->altura_min;
    $altura_max = $request->altura_max;

    $censos = \App\Censo::with('especie')->with('calle')->with('imagenes')->orderBy('id');

    if ( $especie )
        $censos = $censos->where('especie_id', $especie);

    if ( $estado )
        $censos = $censos->where('estado', $estado);

    if ( $tamanio )
        $censos = $censos->where('tamanio', $tamanio);

    if ( $calle )
        $censos = $censos->where('calle_id', $calle);

    if ( $altura_min )
        $censos = $censos->where('altura', '>=', $altura_min);

    if ( $altura_max )
        $censos = $censos->where('altura', '<=', $altura_max);

    $censos = $censos->where('status', \App\Censo::APROBADO);

    return response()->json($censos->get());
});
