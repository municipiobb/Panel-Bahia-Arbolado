<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $fillable = [
    	'delegacion',
    	'localidad_id',
    	'nombre'
    ];
}
