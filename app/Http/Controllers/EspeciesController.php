<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Especie;
use App\Http\Requests;
use App\Http\Requests\EspecieRequest;

class EspeciesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth', ['except' => ['getAll']]);
    }

    public function index(){
    	$especies = Especie::paginate(15);
    	return view('especies.index', compact('especies'));
    }

    public function getAll(Request $request, $date = ''){
      $data = [];
      if($date){
        if(Especie::where('updated_at', '>', \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $date))->count())
          $data = Especie::get(['id', 'nombre', 'updated_at']);
      }else{
        $data = Especie::get(['id', 'nombre']);
      }

      return response()->json([
        'data' => $data
        ]);
    }

    public function create(){
     return view('especies.create');
   }

   public function store(EspecieRequest $request){
     if (Especie::create($request->all())) {
      flash('Especie Creada.', 'success');
      return redirect()->route('especies.index');
    }
  }

  public function edit($id){
   $especie = Especie::findOrFail($id);

   return view('especies.edit', compact('especie'));
 }

 public function update(EspecieRequest $request, $id){
   $especie = Especie::findOrFail($id);

   $especie->update($request->all());

   flash('Especie Actualizada.', 'success');

   return redirect()->route('especies.index');

 }

 public function destroy($id){
   $especie = Especie::findOrFail($id);

   $especie->delete();

    	//flash('Especie Eliminada.', 'warning');

    	return "";//redirect()->route('especies.index');
    }
  }