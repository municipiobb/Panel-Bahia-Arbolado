<?php

namespace App\Acme;

/**
* 
*/
class Helpers
{
	public static function getLocalidad($id){
		$localidades = Constantes::getLocalidades();

		if(isset($localidades[$id]))
			return $localidades[$id];
		return "";
	}
}