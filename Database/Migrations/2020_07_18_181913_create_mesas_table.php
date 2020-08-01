<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mapa_id')->unsigned();
            $table->foreign('mapa_id')->references('id')->on('mapas');
            $table->integer('numero');
            $table->integer('top')->default(0);
            $table->integer('left')->default(0);
            $table->boolean('bloqueada')->default(0);
            $table->integer('config_id')->unsigned();
            $table->foreign('config_id')->references('id')->on('mesas_tipo_configs');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mesas');
    }
}
