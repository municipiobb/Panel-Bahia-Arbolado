<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string url
 */
class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
    	'url',
    	'imagen',
        'imagen_id'
    ];

    public function censo(){
      return $this->belongsTo(Censo::class);
    }
}
