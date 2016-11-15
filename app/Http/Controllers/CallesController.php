<?php

namespace App\Http\Controllers;

use App\Calle;
use App\Acme\Constantes;
use App\Http\Requests\CalleRequest;
use Illuminate\Http\Request;

use App\Http\Requests;

class CallesController extends Controller
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

    public function index(Request $request){

        if($request->has('q')) {
            $calles = Calle::where('nombre', 'LIKE', '%'.$request->get('q').'%')->paginate(20);
        }else{
            $calles = Calle::paginate(20);
        }
        
        $calles->setPath('http://arboladoapp.bahiablanca.gob.ar/calles');

        return view('calles.index', compact('calles'));
    }

    public function create(){

        $localidades = Constantes::getlocalidades();

        return view('calles.create', compact('localidades'));
    }

    public function store(CalleRequest $request){
    	$calle = Calle::create($request->all());

    	return response()->redirectTo('calles');
    }

    public function edit($id){
    	$calle = Calle::find($id);

        $localidades = Constantes::getlocalidades();

        return view('calles.edit', compact('calle', 'localidades'));
    }

    public function update(CalleRequest $request, $id){
    	$calle = Calle::find($id)->update($request->all());

    	return response()->redirectTo('calles');
    }

    public function destroy($id){
    	$calle = Calle::find($id);

    	if(\App\Arbol::where('direccion', $calle->nombre)->count() == 0){
    		if($calle->delete()){
    			return response()->json([
    				'success' => 1,
    				'flash' => 'Calle eliminada.']);
    		}
    	}else{
    		return response()->json([
                'success' => 0,
                'flash' => 'No se puede eliminar la calle, porque se encuentra asociada a un censo.']);
    	}
    }
}
