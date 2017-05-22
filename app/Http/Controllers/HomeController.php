<?php

namespace App\Http\Controllers;

use App\Censo;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $censos = Censo::all();

        $bars = Censo::join('especies', 'censos.especie_id', '=', 'especies.id')
            ->select('especies.id', 'especies.nombre', DB::raw('COUNT(censos.especie_id) as total'))
            ->groupBy('especies.id', 'censos.especie_id', 'especies.nombre')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        $barras = [];
        foreach ($bars as $barra) {
            $barras[] = [
                'id' => (int)$barra->id,
                'nombre' => $barra->nombre,
                'total' => (int)$barra->total
            ];
        }
        return view('home', compact('censos', 'barras'));
    }
}
