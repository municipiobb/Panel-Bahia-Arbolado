<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArboles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('especie_id')->unsigned();
            $table->string('estado');
            $table->string('tamanio');
            $table->string('diametro_tronco');
            $table->string('ancho_vereda');
            $table->string('tipo_vereda');
            $table->string('cantero');
            $table->integer('localidad_id');
            $table->string('delegacion')->default("");
            $table->integer('calle_id')->unsigned()->default(0);
            $table->string('direccion');
            $table->integer('altura');
            $table->string('observaciones')->default("");
            $table->decimal('lat', 8, 6)->default(0);
            $table->decimal('long', 8, 6)->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('especie_id')
              ->references('id')->on('especies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('censos');
    }
}
