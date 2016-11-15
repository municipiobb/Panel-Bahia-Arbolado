<?php

use App\AndroidLog;
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

Route::post('reportpath',function(Request $request){
	$fichero = '/var/www/laravel/public/uploads/android_bug_'.date('d-m-Y').'_'.time().'.txt';

	AndroidLog::create(['error'=> json_encode($request->all())]);

	$myfile = fopen($fichero, "w") or die("Unable to open file!");
	//$txt = "John Doe\n";
	@fwrite($myfile, json_encode($request->all()));
	//$txt = "Jane Doe\n";
	//@fwrite($myfile, $request->all());
	@fclose($myfile);

});

Route::get('/calles/{date?}', function($date=''){
	$data = [];
	if($date){
		if(\App\Calle::where('updated_at', '>', \Carbon\Carbon::createFromFormat('d-m-Y H:i:s',$date))->count())
			$data = \App\Calle::all();
	}else{
		$data = \App\Calle::all();
	}

	return response()->json([
		'data' => $data
		]);
});

Route::get('/especies/{date?}', 'EspeciesController@getAll');

Route::get('/getFormData', 'ApiController@getFormData');

Route::get('/censos', 'ApiController@getAll');
Route::post('/censo', 'ApiController@saveCenso');
Route::post('/censo/{id}/imagenes', 'ApiController@saveImagen');

Route::get('/user', function (Request $request) {
	return $request->user();
});//->middleware('auth:api');

Route::post('mapa_ll', function(Request $request){
	$especie = $request->especie;
	$estado = $request->estado;
	$tamanio = $request->tamanio;

	$censos = \App\Censo::with('especie')->with('calle')->with('imagenes')->orderBy('id');

	if($especie)
		$censos = $censos->where('especie_id', $especie);

	if($estado)
		$censos = $censos->where('estado', $estado);

	if($tamanio)
		$censos = $censos->where('tamanio', $tamanio);


	return response()->json($censos->get());
});
