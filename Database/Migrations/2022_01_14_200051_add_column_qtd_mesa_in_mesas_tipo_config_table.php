<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQtdMesaInMesasTipoConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mesas_tipo_configs', function (Blueprint $table) {
            $table->float('qtd_mesa')->default(1)->after('background_color_reversada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mesas_tipo_configs', function (Blueprint $table) {
            $table->dropColumn('qtd_mesa');
        });
    }
}
