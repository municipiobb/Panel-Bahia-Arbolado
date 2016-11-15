<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Image;
use App\Censo;
use App\Calle;
use App\Acme\Constantes;
use App\Especie;
use App\Http\Requests;
use App\Http\Requests\CensoRequest;
use Illuminate\Support\Facades\Validator;

class CensosController extends Controller
{

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(){
    $censos = Censo::orderBy('id', 'asc')->paginate(15);
    return view('censos.index', compact('censos'));
  }

  public function store(Request $request){
    $censo = $request->json()->all();

    $validator = Validator::make(
    $censo,
    [
      'especie_id' => 'required|exists:especies,id',
      'estado' => 'required',
      'tamanio' => 'required',
      'diametro_tronco' => 'required',
      'ancho_vereda' => 'required',
      'tipo_vereda' => 'required',
      'cantero' => 'required',
      'direccion' => 'required',
      'altura' => 'required'
    ]
  );

  if ($validator->fails())
  {
    return [
      "estado" => 0,
      "mensaje" => $validator->errors()
    ];

  }

  $model = Censo::create($censo);

  if($model)
  return response()->json([
    'success' => 1,
    'flash' => 'Censo Guardado.'
  ]);
  else
  return response()->json([
    'success' => 0,
    'flash' => 'Ocurrio un error.'
  ]);
}

public function edit($id){
  $censo = Censo::findOrFail($id);
  $especies = Especie::pluck('nombre', 'id');
  $estados = Constantes::getEstados();
  $diametros = Constantes::getDiametros();
  $anchos_veredas = Constantes::getAnchosVereda();
  $tipos_vereda = Constantes::getTipoVereda();
  $canteros = Constantes::getCantero();
  $tamanios = Constantes::getTamanios();
  $calles = Calle::pluck('nombre', 'id');

  $localidades = Constantes::getLocalidades();

  return view('censos.edit', compact('censo', 'especies', 'estados', 'diametros', 'anchos_veredas', 'tipos_vereda', 'calles', 'localidades', 'canteros', 'tamanios'));
}

public function update(Request $request, $id){
  $censo = Censo::findOrFail($id);
  $calle = Calle::findOrFail($request->calle_id)->nombre;

  /**
  *  Obtener latitud y longitud de google maps.
  */
  $aContext = array(
    'http' => array(
      'proxy' => '10.240.3.8:3128'
    ),
  );
  $cxContext = stream_context_create($aContext);

  //$sFile = file_get_contents("http://www.google.com", False, $cxContext);

  $address = $calle. '+'.$request->altura.'+Bahia+Blanca,+Buenos+Aires'; // Google HQ
  $prepAddr = str_replace(' ','+',$address);
  $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false', False, $cxContext);
  $output= json_decode($geocode);
  $censo->lat = $output->results[0]->geometry->location->lat;
  $censo->long = $output->results[0]->geometry->location->lng;


  if( $censo->update($request->all()) )
  flash('Censo actualizado!', 'success');

  $this->saveImagen($request, $censo);

  return redirect()->route('index');
}

public function show($id){
  $censo = Censo::findOrFail($id);

  return view('censos.show', compact('censo'));
}

public function aprobar($id){
  $censo = Censo::findOrFail($id);
  $censo->status = Censo::APROBADO;
  $censo->save();

  return response()->json(['success'=>1,'flash'=>'Censo Aprobado']);
}

public function borrarImagen($id){
  $imagen = \App\Imagen::findOrFail($id);
  $imagen->delete();

  return response()->json(['success'=>1,'flash'=>'Imagen Borrada.']);
}

public function destroy($id){
  $imagen = \App\Censo::findOrFail($id);
  $imagen->delete();

  return response()->json(['success'=>1,'flash'=>'Imagen Borrada.']);
}

public function saveImagen(Request $request, $censo){
  if ($request->hasFile('image')) {
    $image = $request->file('image');
    $filename = time() . '.' . $image->getClientOriginalExtension();
    $relative_path = 'uploads' . DIRECTORY_SEPARATOR . $filename;
    $path = public_path($relative_path);
    $resized = Image::make($image->getRealPath())->resize(400, null, function ($constraint) {
      $constraint->aspectRatio();
    });
    $resized->save($path);

    $img_data = file_get_contents($path);
    $img = base64_encode($img_data);

    $aux = $censo->imagenes()->create([
      'url' => $relative_path,
      'imagen' => $img
    ]);

    return response()->json($aux);
  }
}
}
