<?php

namespace App\Http\Controllers;

use App\Acme\GoogleMapsGeocoder;
use Image;
use App\Censo;
use App\Calle;
use App\Imagen;
use App\Especie;
use App\Acme\Constantes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CensosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $especie_id = $request->get('especie', '');
        $estado = $request->get('estado', '');
        $tamanio = $request->get('tamanio', '');
        $diametro = $request->get('diametro', '');
        $ancho_vereda = $request->get('ancho_vereda', '');
        $tipo_vereda = $request->get('tipo_vereda', '');

        $calle = request('calle');
        $altura_min = request('altura_min');
        $altura_max = request('altura_max');

        /** @var Censo $censos */
        $censos = Censo::query();

        if ($especie_id)
            $censos = $censos->where('especie_id', $especie_id);

        if ($estado)
            $censos = $censos->where('estado', $estado);

        if ($tamanio)
            $censos = $censos->where('tamanio', $tamanio);

        if ($diametro)
            $censos = $censos->where('diametro_tronco', $diametro);

        if ($ancho_vereda)
            $censos = $censos->where('ancho_vereda', $ancho_vereda);

        if ($tipo_vereda)
            $censos = $censos->where('tipo_vereda', $tipo_vereda);

        if ($calle)
            $censos = $censos->where('calle_id', $calle);

        if ($altura_min)
            $censos = $censos->where('altura', '>=', $altura_min);

        if ($altura_max)
            $censos = $censos->where('altura', '<=', $altura_max);

        $censos = $censos->orderBy('id', 'desc')->paginate(15);

        $especies = Especie::getDropDown();

        $calles = Calle::getDropDown();

        return view('censos.index', compact('censos', 'especies', 'calles'));
    }

    public function store(Request $request)
    {
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

        if ($validator->fails()) {
            return [
                "estado" => 0,
                "mensaje" => $validator->errors()
            ];

        }

        $model = Censo::create($censo);

        if ($model)
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

    public function edit($id)
    {
        /** @var Censo $censo */

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

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            flash('No tiene permiso para actualizar el registro.', 'warning');
            return redirect()->route('index');
        }

        /** @var Censo $censo */
        $censo = Censo::findOrFail($id);
        $calle = Calle::findOrFail(request('calle_id'))->nombre;

        $geo = new GoogleMapsGeocoder();

        $geo->setProxy(env('HTTP_PROXY'), 'HTTP_PROXY_PORT');
        $geo->setApiKey(env('GOOGLE_API_KEY'));

        $geo = $this->geolocalizar($calle, $censo->altura, $geo);

        $censo->lat = $geo['lat'];
        $censo->long = $geo['long'];

        if ($censo->update($request->all()))
            flash('Censo actualizado!', 'success');

        $this->saveImagen($request, $censo);

        return redirect()->route('censos.show', $censo->id);
    }

    /**
     * @param Censo $censo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Censo $censo)
    {
        return view('censos.show', compact('censo'));
    }

    /**
     * @param Censo $censo
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprobar(Censo $censo)
    {
        $censo->status = Censo::APROBADO;
        $censo->save();

        return response()->json([
            'success' => 1,
            'flash' => 'Censo Aprobado'
        ]);
    }

    public function borrarImagen($id)
    {
        /** @var Imagen $imagen */
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        $res = Storage::disk('public')->delete($imagen->url);

        return response()->json([
            'success' => 1,
            'flash' => 'Imagen Borrada.'
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            flash('No tiene permiso para eliminar el registro.', 'warning');
            return redirect()->route('index');
        }

        /** @var Censo $censo */
        $censo = Censo::findOrFail($id);

        $imagenes = $censo->imagenes;

        foreach ($imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->url);
        }

        $censo->delete();

        return response()->json([
            'success' => 1,
            'flash' => 'Censo Borrado.'
        ]);
    }

    /**
     * @param Request $request
     * @param $censo
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImagen(Request $request, Censo $censo)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $relative_path = 'uploads' . DIRECTORY_SEPARATOR . $filename;
            $path = public_path($relative_path);

            /** @var \Intervention\Image\Image $resized */
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
        } else {
            return response()->json([]);
        }
    }

    /**
     * @param $calle
     * @param $altura
     * @param GoogleMapsGeocoder $geo
     * @return array
     */
    private function geolocalizar($calle, $altura, $geo)
    {
        $geo->setAddress($calle . '+' . $altura . ', Bahia Blanca, Buenos Aires');

        try {
            $response = $geo->geocode(true);

            if (isset($response['status'])) {
                if ($response['status'] == 'OK') {
                    $lat = $response['results'][0]['geometry']['location']['lat'];
                    $long = $response['results'][0]['geometry']['location']['lng'];

                    return [
                        "lat" => $lat,
                        "long" => $long
                    ];
                }
            }
        } catch (\Exception $e) {
            $errors['Exception'] = $e->getMessage();
        }

        return [
            "lat" => 0,
            "long" => 0
        ];
    }
}
