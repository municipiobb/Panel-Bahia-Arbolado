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

    public static function getDropDown()
    {
        return static::query()->whereHas('censos', function ($query){
            $query->where('status', Censo::APROBADO);
        })->pluck('nombre', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function censos(){
        return $this->hasMany(Censo::class);
    }
}
