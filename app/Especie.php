<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $fillable = [
    	'nombre'
    ];

    public function arboles(){
    	return $this->hasMany(Arbol::class);
    }

    public function hasArboles(){
    	if($this->arboles()->count())
    		return true;
    	else
    		return false;
    }
}
