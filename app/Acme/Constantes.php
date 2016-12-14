<?php

namespace App\Acme;

/**
* 
*/
class Constantes
{


	public static function getTamanios(){
		return [
		'Chico' => 'Chico',
		'Mediano' => 'Mediano',
		'Grande' => 'Grande'
		];
	}

	public static function getLocalidades(){
		return [
		'1' => 'Bahía Blanca',
		'2' => 'Cabildo',
		'3' => 'General Cerri',
		'4' => 'Ingeniero White'
		];
	}

	public static function getEstados(){
		return [
		'Bueno' => 'Bueno',
		'Regular' => 'Regular',
		'Malo' => 'Malo'
		];
	}

	public static function getDiametros(){
		return [
		'-30 cm' => '-30 cm',
		'+50 cm' => '+50 cm',
		'30-50 cm' => '30-50 cm'
		];
	}

	public static function getAnchosVereda(){
		return [
		'-2 mts' => '-2 mts',
		'+3.5 mts' => '+3.5 mts',
		'2-3.5 mts' => '2 - 3.5 mts'
		];
	}

	public static function getTipoVereda(){
		return [
		'Baldosa' => 'Baldosa',
		'Tierra' => 'Tierra',
		'Tierra y cesped' => 'Tierra y cesped',
		'Tierra, cesped y baldosa' => 'Tierra, cesped y baldosa'
		];
	}

	public static function getCantero(){
		return [
		'Si (Elevado)' => 'Si (Elevado)',
		'Si (No elevado)' => 'Si (No elevado)',
		'Sin Cantero' => 'Sin Cantero'
		];
	}
}