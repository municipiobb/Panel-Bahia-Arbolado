<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Calle::class, function(Faker\Generator $faker) {
    return [
        'nombre' => $faker->name,
        // 'localidad_id' => null
    ];
});

$factory->define(App\Especie::class, function(Faker\Generator $faker) {
    return [
        'nombre' => $faker->name
    ];
});

$factory->define(App\Censo::class, function(Faker\Generator $faker){
    return [
        'especie_id' => factory('App\Especie')->create()->id,
        'estado' => 'Bueno',
        'tamanio' => 'Grande',
        'diametro_tronco' => 'Ancho',
        'ancho_vereda'=> 'Ancho',
        'tipo_vereda'=> 'Vereda 1',
        'cantero' => 'No',
        // 'delegacion' => null,
        'localidad_id' => 1,
        'calle_id' => factory('App\Calle')->create()->id,
        'direccion' => '',
        'altura' => 1500,
        'lat' => 0,
        'long' => 0,
        'observaciones' => $faker->paragraph
    ];
});