<?php

Route::group(['middleware' => 'web', 'prefix' => 'mapademesas', 'as'=>'mapademesas.', 'namespace' => 'Modules\MapaDeMesas\Http\Controllers'], function()
{
    //Route::get('/', 'MapaDeMesasController@index')->name('index');

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as'=>'admin.'], function()
    {
        Route::get('mapa', 'MapaController@index')->name('mapa.index');
        Route::post('mapa/datatable', 'MapaController@datatable')->name('mapa.datatable');

        Route::get('mapa/{id}/manutencao', 'MapaManutencaoController@index')->name('mapa.manutencao');
        Route::post('mapa/{id}/manutencao/upload', 'MapaManutencaoController@upload')->name('mapa.manutencao.upload');
        Route::post('mapa/{id}/manutencao/editXY/{x}/{y}', 'MapaManutencaoController@editXY')->name('mapa.manutencao.editXY');
        Route::put('mapa/{mapa}/manutencao/edit-config/{config}', 'MapaManutencaoController@editConfig')->name('mapa.manutencao.editconfig');
        Route::put('mapa/{mapa}/manutencao/mapa/block{config}', 'MapaManutencaoController@editConfig')->name('mapa.manutencao.editconfig');

        Route::get('mapa/{id}/manutencao/mesa/add', 'MesaManutencaoController@add')->name('mapa.manutencao.mesa.add');
        Route::get('mapa/{id}/manutencao/mesa/listar', 'MesaManutencaoController@listar')->name('mapa.manutencao.mesa.listar');

        Route::get('mapa/{id}/manutencao/mesa/edit-top/{mesa}/{top}', 'MesaManutencaoController@editTop')->name('mapa.manutencao.mesa.edittop');
        Route::get('mapa/{id}/manutencao/mesa/edit-left/{mesa}/{left}', 'MesaManutencaoController@editLeft')->name('mapa.manutencao.mesa.editleft');
        Route::get('mapa/{id}/manutencao/mesa/del/{mesa}', 'MesaManutencaoController@del')->name('mapa.manutencao.mesa.del');
        Route::put('mapa/{mapa}/manutencao/mesa/block/{mesa}', 'MesaManutencaoController@block')->name('mapa.manutencao.mesa.block');
    });

    Route::group(['prefix' => 'portal', 'namespace' => 'Portal', 'as'=>'portal.'], function()
    {
        Route::get('mapas', 'MapaController@index')->name('mapas.index');
        Route::get('mapa/{mapa}/produto/{produto}/escolher', 'MapaController@escolher')->name('mapa.escolher');

        Route::post('mapa/{mapa}/produto/{produto}/escolher/mesa/{mesa}', 'MesaController@escolher')->name('mapa.escolher.mesa');

    });

});
