<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int id
 * @property int especie_id
 * @property string nombre
 * @property int lat
 * @property int long
 * @property int status
 * @property Collection imagenes
 */
class Censo extends Model
{
	const APROBADO = 1;

	const ESTADO_BUENO = 'Bueno';
	const ESTADO_REGULAR = 'Regular';
	const ESTADO_MALO = 'Malo';

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imagenes(){
		return $this->hasMany(Imagen::class);
	}
}
