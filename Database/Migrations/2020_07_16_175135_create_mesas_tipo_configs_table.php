<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMesasTipoConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesas_tipo_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('width');
            $table->integer('height');
            $table->integer('radius');
            $table->integer('line_height');
            $table->integer('font_size');
            $table->string('color_livre');
            $table->string('background_color_livre');
            $table->string('color_ocupada');
            $table->string('background_color_ocupada');
            $table->string('color_reversada');
            $table->string('background_color_reversada');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('mesas_tipo_configs');
    }
}
