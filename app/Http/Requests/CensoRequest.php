<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CensoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'especie_id' => 'required|exists:especies,id',
            'estado' => 'required',
            'tamanio' => 'required',
            'diametro_tronco' => 'required',
            'ancho_vereda' => 'required',
            'tipo_vereda' => 'required',
            'cantero' => 'required',
            'direccion' => 'required',
            'altura' => 'required'
        ];
    }
}
