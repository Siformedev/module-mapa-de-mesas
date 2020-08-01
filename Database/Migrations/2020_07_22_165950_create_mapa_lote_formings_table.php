<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapaLoteFormingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapa_lote_formings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mapa_id')->unsigned();
            $table->foreign('mapa_id')->references('id')->on('mapas')->onDelete('cascade');
            $table->integer('forming_id')->unsigned();
            $table->foreign('forming_id')->references('id')->on('formings')->onDelete('cascade');
            $table->integer('lote')->unsigned();
            $table->datetime('data_inicio')->nullable();
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
        Schema::dropIfExists('mapa_lote_formings');
    }
}
