<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMesaEscolhidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesa_escolhidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesas')->onDelete('cascade');
            $table->integer('mapa_id')->unsigned();
            $table->foreign('mapa_id')->references('id')->on('mapas')->onDelete('cascade');
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->integer('forming_id')->unsigned();
            $table->foreign('forming_id')->references('id')->on('formings')->onDelete('cascade');
            $table->integer('fps_id')->unsigned();
            $table->foreign('fps_id')->references('id')->on('formando_produtos_e_servicos');
            $table->boolean('cancelado')->default(0);
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
        Schema::dropIfExists('mesa_escolhidas');
    }
}
