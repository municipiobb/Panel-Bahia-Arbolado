<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
    	'url',
    	'imagen'
    ];

    public function censo(){
      return $this->belongsTo(Censo::class);
    }
}