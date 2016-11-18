<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int especie_id
 * @property string nombre
 */
class Censo extends Model
{
	const APROBADO = 1;

	protected $table = 'censos';

	protected $fillable = [
		'nombre',
		'especie_id',
		'estado',
		'tamanio',
		'diametro_tronco',
		'ancho_vereda',
		'tipo_vereda',
		'cantero',
		'delegacion',
		'localidad_id',
		'calle_id',
		'direccion',
		'altura',
		'lat',
		'long',
		'observaciones'
	];

	public $sortable = [
		'nombre',
		'especie_id',
		'estado',
		'tamanio',
		'diametro_tronco',
		'ancho_vereda',
		'tipo_vereda',
		'cantero',
		'direccion',
		'altura',
		'localidad',
	];

	public function especie(){
		return $this->belongsTo(Especie::class);
	}

	public function calle(){
		return $this->belongsTo(Calle::class);
	}

	public function imagenes(){
		return $this->hasMany(Imagen::class);
	}
}
