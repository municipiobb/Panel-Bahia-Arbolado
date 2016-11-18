<?php

namespace App\Http\Controllers;

use App\Calle;
use App\Censo;
use App\Acme\Constantes;
use Illuminate\Http\Request;
use App\Http\Requests\CalleRequest;

class CallesController extends Controller
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
        /** @var Calle $calles */
        if ( $request->has('q') ) {
            $calles = Calle::where('nombre', 'LIKE', '%' . $request->get('q') . '%')->paginate(20);
        } else {
            $calles = Calle::paginate(20);
        }

        $calles->setPath('http://arboladoapp.bahiablanca.gob.ar/calles');

        return view('calles.index', compact('calles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        $localidades = Constantes::getLocalidades();

        return view('calles.create', compact('localidades'));
    }

    /**
     * @param CalleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CalleRequest $request)
    {
        $calle = Calle::create($request->all());

        if($calle)
            flash('Calle guardada.', 'success');
        else
            flash('Ah ocurrido un error.', 'warning');

        return response()->redirectTo('calles');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $calle = Calle::findOrFail($id);

        $localidades = Constantes::getLocalidades();

        return view('calles.edit', compact('calle', 'localidades'));
    }

    /**
     * @param CalleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CalleRequest $request, $id)
    {
        $calle = Calle::find($id)->update($request->all());

        if($calle)
            flash('Calle actualizada.', 'success');
        else
            flash('Ah ocurrido un error.', 'warning');

        return response()->redirectTo('calles');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        /** @var Censo $calle */
        $calle = Calle::find($id);

        if ( Censo::where('direccion', $calle->nombre)->count() == 0 ) {
            if ( $calle->delete() ) {
                return response()->json([
                    'success' => 1,
                    'flash' => 'Calle eliminada.']);
            } else {
                return response()->json([
                    'success' => 0,
                    'flash' => 'Ah ocurrido un error.']);
            }
        } else {
            return response()->json([
                'success' => 0,
                'flash' => 'No se puede eliminar la calle, porque se encuentra asociada a un censo.']);
        }
    }
}
