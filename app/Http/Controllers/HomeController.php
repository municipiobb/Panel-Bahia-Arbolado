<?php

namespace App\Http\Controllers;

use App\Censo;
use App\Especie;
use Illuminate\Http\Request;

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


        $censos = $censos->paginate(15);

        $especies = Especie::orderBy('nombre')->all();

        return view('home', compact('censos', 'especies'));
    }
}
