<?php

Route::group(['middleware' => 'web', 'prefix' => 'mapademesas', 'as'=>'mapademesas.', 'namespace' => 'Modules\MapaDeMesas\Http\Controllers'], function()
{
    Route::get('/', 'MapaDeMesasController@index')->name('index');

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as'=>'admin.'], function()
    {
        Route::get('mapa', 'MapaController@index')->name('mapa.index');
        Route::post('mapa/datatable', 'MapaController@datatable')->name('mapa.datatable');
    });

});
