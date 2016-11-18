<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $fillable = [
    	'nombre'
    ];

    public function censos(){
        return $this->hasMany(Censo::class);
    }

    public function hasCensos(){
    	if($this->censos()->count())
    		return true;
    	else
    		return false;
    }
}
