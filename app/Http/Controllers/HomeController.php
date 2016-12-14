<?php

namespace App\Http\Controllers;

use App\Censo;
use App\Especie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
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
        $censos = Censo::orderBy('id', 'desc');

        if($especie_id)
            $censos = $censos->where('especie_id', $especie_id);

        if($estado)
            $censos = $censos->where('estado', $estado);

        if($tamanio)
            $censos = $censos->where('tamanio', $tamanio);

        if($diametro)
            $censos = $censos->where('diametro_tronco', $diametro);

        if($ancho_vereda)
            $censos = $censos->where('ancho_vereda', $ancho_vereda);

        if($tipo_vereda)
            $censos = $censos->where('tipo_vereda', $tipo_vereda);

        if ( $calle )
            $censos = $censos->where('calle_id', $calle);

        if ( $altura_min )
            $censos = $censos->where('altura', '>=', $altura_min);

        if ( $altura_max )
            $censos = $censos->where('altura', '<=', $altura_max);

        $censos = $censos->paginate(15);

        //$especies = Especie::orderBy('nombre')->get();

        $especies = DB::table('especies')
            ->select('especies.id', 'especies.nombre')
            ->join('censos','censos.especie_id','=','especies.id')
            ->where('censos.status', Censo::APROBADO)
            ->groupBy('especies.id', 'especies.nombre')
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        $calles = DB::table('calles')
            ->select('calles.id', 'calles.nombre')
            ->join('censos','censos.calle_id','=','calles.id')
            ->where('calles.localidad_id', 1)
            ->where('censos.status', Censo::APROBADO)
            ->groupBy('calles.id', 'calles.nombre')
            ->orderBy('nombre')
            ->pluck('nombre', 'id');

        return view('home', compact('censos', 'especies', 'calles'));
    }
}
