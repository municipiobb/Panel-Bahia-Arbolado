<?php

namespace App\Http\Controllers;

use App\Calle;
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
        $calles = Calle::query();

        if ( $request->has('q') ) {
            $calles = $calles->where('nombre', 'LIKE', '%' . $request->get('q') . '%');
        }

        $calles = $calles->paginate(20);

        $calles->setPath(url('calles'));

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
        if(!auth()->user()->isAdmin()) {
            flash('No tiene permiso para crear un nuevo registro.', 'warning');
            return response()->redirectTo('calles');
        }

        $calle = Calle::create($request->all());

        if($calle)
            flash('Calle guardada.', 'success');
        else
            flash('Ah ocurrido un error.', 'warning');

        return response()->redirectTo('calles');
    }

    /**
     * @param Calle $calle
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Calle $calle)
    {
        // $calle = Calle::findOrFail($id);

        $localidades = Constantes::getLocalidades();

        return view('calles.edit', compact('calle', 'localidades'));
    }

    /**
     * @param CalleRequest $request
     * @param Calle $calle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CalleRequest $request, Calle $calle)
    {
        if(!auth()->user()->isAdmin()) {
            flash('No tiene permiso para actualizar el registro.', 'warning');
            return response()->redirectTo('calles');
        }

        $calle = $calle->update($request->all());

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
        if(!auth()->user()->isAdmin()) {
            flash('No tiene permiso para eliminar el registro.', 'warning');
            return redirect()->route('index');
        }
        /** @var Calle $calle */
        $calle = Calle::find($id);

        /**
         * Solo se permite borrar una calle si esta no tiene censos asociados
         */
        if ( !$calle->hasCensos() ) {
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