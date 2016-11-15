<?php

namespace App\Http\Controllers;

use Image;
use App\Censo;
use Validator;
use App\Especie;
use App\Acme\Constantes;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function getFormData(){

        $especies = Especie::pluck('nombre', 'id');

        $res = [
        'especies' => $especies,
        'estados' => Constantes::getEstados(),
        'diametros' => Constantes::getDiametros(),
        'anchos_vereda' => Constantes::getAnchosVereda(),
        'tipos_vereda' => Constantes::getTipoVereda(),
        'canteros' => Constantes::getCantero()
        ];

        return response()->json($res);
    }

    public function getAll(Request $request){

        $especie = $request->especie;
        $estado = $request->estado;
        $tamanio = $request->tamanio;

        $censos = \App\Censo::orderBy('id');

        if($especie)
            $censos = $censos->where('especie_id', $especie);

        if($estado)
            $censos = $censos->where('estado', $estado);

        if($tamanio)
            $censos = $censos->where('tamanio', $tamanio);

        $censos = Censo::where('status', 1)->with(['imagenes', 'especie'])->get();

        return response()->json(['data'=>$censos]);
    }

    public function saveCenso(Request $request){
    	$arbol = $request->all();

    	$validator = Validator::make(
            $arbol,
            [
                //'especie_id' => 'required|exists:especies,id',
                //'estado' => 'required',
                //'tamanio' => 'required',
                //'diametro_tronco' => 'required',
                //'ancho_vereda' => 'required',
                //'tipo_vereda' => 'required',
                //'cantero' => 'required',
            'localidad_id' => 'required',
            'calle_id' => 'required',
            'direccion' => 'required',
            'altura' => 'required',
            ]
            );

        if ($validator->fails())
        {
            return [
            "estado" => 0,
            "mensaje" => $validator->errors()
            ];
        }


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

        $address = $arbol['direccion']. '+'.$arbol['altura'].'+Bahia+Blanca,+Buenos+Aires'; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false', False, $cxContext);
        $output= json_decode($geocode);
        $arbol['lat'] = $output->results[0]->geometry->location->lat;
        $arbol['long'] = $output->results[0]->geometry->location->lng;


        $nextCenso = Censo::where('direccion', $arbol['direccion'])->where('altura', $arbol['altura'])->count();

        $model = Censo::create($arbol);

        if($model)
        	return response()->json([
        		'success' => 1,
                'arbol_id' => $model->id,
                'arbol' => $model->toArray(),
                'flash' => 'Censo Guardado.'
                ]);
        else
        	return response()->json([
        		'success' => 0,
        		'flash' => 'Ocurrio un error.'
        		]);
    }

    /**
     * Guarda imagen en el directorio publico
     */
    public function saveImagen(Request $request, $id_arbol){

        $arbol = Censo::find($id_arbol);

        if(!$arbol){
            return response()->json([
                'success' => 0,
                'flash'=>'El censo no existe'
                ]);
        }
        //
        //$img = base64_encode($imagen);


        $imagen = $request->get('imagen');

        
        if ($imagen) {

            $filename = 'imagen_arbolado_' . time().'_'. rand().'.jpg';

            $relative_path = 'uploads' . DIRECTORY_SEPARATOR . $filename;
            
            $path = public_path($relative_path);

            $img = Image::make(base64_decode($imagen));

            if($img->height() > $img->width()){
                $img->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }else{
                $img->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $img->save($path);
            
            $aux = $arbol->imagenes()->create([
                'nombre' => $filename,
                'url' => $relative_path,
                'imagen' => ''
                ]);

            if($aux)
                return response()->json([
                    'success' => 1,
                    'imagen' => $aux
                    ]);
            else
                return response()->json([
                    'success' => 0,
                    'imagen' => null
                    ]);
        }
    }
}
