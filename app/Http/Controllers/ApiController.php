<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Image;
use App\Censo;
use Validator;
use App\Especie;
use App\Acme\Constantes;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function getFormData()
    {
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

    public function saveCenso(Request $request)
    {
        $arbol = $request->all();

        $validator = Validator::make(
            $arbol,
            [
                'localidad_id' => 'required',
                'calle_id' => 'required',
                'direccion' => 'required',
                'altura' => 'required',
            ]
        );

        if ($validator->fails()) {
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

        $address = $arbol['direccion'] . '+' . $arbol['altura'] . '+Bahia+Blanca,+Buenos+Aires'; // Google HQ
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false', False, $cxContext);
        $output = json_decode($geocode);
        if ($output->status != 'ZERO_RESULTS') {
            $arbol['lat'] = $output->results[0]->geometry->location->lat;
            $arbol['long'] = $output->results[0]->geometry->location->lng;
        } else {
            $arbol['lat'] = 0;
            $arbol['long'] = 0;
        }

        /** @var Censo $model */
        $model = Censo::create($arbol);

        if ($model)
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
     * @param Request $request
     * @param $id_arbol
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImagen(Request $request, $id_arbol)
    {

        /** @var Censo $arbol */
        $arbol = Censo::find($id_arbol);

        if (!$arbol) {
            return response()->json([
                'success' => 0,
                'flash' => 'El censo no existe'
            ]);
        }

        if ($arbol->imagenes()->where('imagen_id', request('imagen_id'))->first()) {
            return response()->json([
                'success' => 1,
                'flash' => 'La imagen ya existe.'
            ]);
        }

        $imagen = $request->get('imagen');

        if ($imagen) {

            $filename = 'imagen_arbolado_' . time() . '_' . rand() . '.jpg';

            $relative_path = 'uploads' . DIRECTORY_SEPARATOR . $filename;

            $path = public_path($relative_path);

            $img = Image::make(base64_decode($imagen));

            if ($img->height() > $img->width()) {
                $img->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $img->save($path);

            $aux = $arbol->imagenes()->create([
                'imagen_id' => $request->get('imagen_id'),
                'nombre' => $filename,
                'url' => $relative_path,
                'imagen' => ''
            ]);

            if ($aux)
                return response()->json([
                    'success' => 1,
                    'imagen' => $aux
                ]);
            else
                return response()->json([
                    'success' => 0,
                    'imagen' => null
                ]);
        } else
            return response()->json([
                'success' => 0,
                'imagen' => null
            ]);
    }

    public function getAll()
    {
        $censos = DB::table('censos AS c')
            ->join('calles', 'calles.id', '=', 'c.calle_id')
            ->join('especies', 'especies.id', '=', 'c.especie_id')
            ->select(
                'especies.nombre AS especie',
                'c.estado',
                'c.tamanio',
                'c.diametro_tronco',
                'ancho_vereda',
                'tipo_vereda',
                'cantero',
                'direccion',
                'altura',
                'lat',
                'long'
            )
            ->get();
        /*
        $censos = Censo::query()
            ->with('calle')
            ->with('especie')
            ->select('censos.id', 'censos.estado', 'censos.estado')
            ->get();
        */

        return json_encode($censos);
    }
}
