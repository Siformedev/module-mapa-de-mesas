<?php

Route::group(['middleware' => ['web','auth'], 'prefix' => 'mapademesas', 'as'=>'mapademesas.', 'namespace' => 'Modules\MapaDeMesas\Http\Controllers'], function()
{
    //Route::get('/', 'MapaDeMesasController@index')->name('index');

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['checkcollaborator'], 'as'=>'admin.'], function()
    {
        Route::get('mapa', 'MapaController@index')->name('mapa.index');
        Route::post('mapa/datatable', 'MapaController@datatable')->name('mapa.datatable');

        Route::get('mesa', 'MesaController@index')->name('mesa.index');
        Route::get('mesa', 'MesaController@index')->name('mesa.index');
        Route::post('mesa/datatable', 'MesaController@datatable')->name('mesa.datatable');

        Route::get('mesa-tipo-config', 'MesaTipoConfigController@index')->name('mesa-tipo-config.index');
        Route::get('mesa-tipo-config/actives', 'MesaTipoConfigController@actives')->name('mesa-tipo-config.actives');
        Route::get('mesa-tipo-config/{mesaTipoConfig}', 'MesaTipoConfigController@show')->name('mesa-tipo-config.show');
        Route::put('mesa-tipo-config/{mesaTipoConfig}', 'MesaTipoConfigController@update')->name('mesa-tipo-config.update');
        Route::post('mesa-tipo-config/datatable', 'MesaTipoConfigController@datatable')->name('mesa-tipo-config.datatable');

        Route::get('mesas-escolhidas', 'MesasEscolhidasController@index')->name('mesas-escolhidas.index');
        Route::post('mesas-escolhidas/datatable', 'MesasEscolhidasController@datatable')->name('mesas-escolhidas.datatable');
        Route::get('mesas-escolhidas/mapa/{mapa}/mesas', 'MesaController@listar')->name('mesas-escolhidas.mapa.mesas.listar');
        Route::get('mesas-escolhidas/mapa/{mapa}/forming/{forming}', 'MesaController@fpsForming')->name('mesas-escolhidas.mapa.forming.fps');

        Route::get('mapa/{id}/manutencao', 'MapaManutencaoController@index')->name('mapa.manutencao');
        Route::post('mapa/{id}/manutencao/upload', 'MapaManutencaoController@upload')->name('mapa.manutencao.upload');
        Route::post('mapa/{id}/manutencao/editXY/{x}/{y}', 'MapaManutencaoController@editXY')->name('mapa.manutencao.editXY');
        Route::put('mapa/{mapa}/manutencao/edit-config/{config}', 'MapaManutencaoController@editConfig')->name('mapa.manutencao.editconfig');
        Route::put('mapa/{mapa}/manutencao/mapa/block{config}', 'MapaManutencaoController@editConfig')->name('mapa.manutencao.editconfig');

        Route::get('mapa/{id}/manutencao/mesa/add/{top}/{left}/{config}', 'MesaManutencaoController@add')->name('mapa.manutencao.mesa.add');
        Route::get('mapa/{id}/manutencao/mesa/listar', 'MesaManutencaoController@listar')->name('mapa.manutencao.mesa.listar');

        Route::get('mapa/{id}/manutencao/mesa/edit-top-left/{mesa}/{top}/{left}', 'MesaManutencaoController@editTopLeft')->name('mapa.manutencao.mesa.edittopleft');
        Route::get('mapa/{id}/manutencao/mesa/edit-top/{mesa}/{top}', 'MesaManutencaoController@editTop')->name('mapa.manutencao.mesa.edittop');
        Route::get('mapa/{id}/manutencao/mesa/edit-left/{mesa}/{left}', 'MesaManutencaoController@editLeft')->name('mapa.manutencao.mesa.editleft');
        Route::get('mapa/{id}/manutencao/mesa/del/{mesa}', 'MesaManutencaoController@del')->name('mapa.manutencao.mesa.del');
        Route::put('mapa/{mapa}/manutencao/mesa/block/{mesa}', 'MesaManutencaoController@block')->name('mapa.manutencao.mesa.block');

        Route::get('mapa-lote-forming', 'MapaLoteFormingController@index')->name('mapa-lote-forming.index');
        Route::post('mapa-lote-forming/datatable', 'MapaLoteFormingController@datatable')->name('mapa-lote-forming.datatable');

        Route::get('mapa-lote-forming/add', 'MapaLoteFormingController@add')->name('mapa-lote-forming.add');
        Route::get('mapa-lote-forming/add/list', 'MapaLoteFormingController@addList')->name('mapa-lote-forming.addlist');
        Route::get('mapa-lote-forming/contract/{contract}/find/mapas', 'MapaLoteFormingController@getMapasFromContract')->name('mapa-lote-forming.getMapasFromContract');
        Route::post('mapa-lote-forming/contract/{contract}/mapa/{mapa}', 'MapaLoteFormingController@updateOrCreate')->name('mapa-lote-forming.updateOrCreate');
        Route::delete('mapa-lote-forming/contract/{contract}/mapa/{mapa}/remove', 'MapaLoteFormingController@remove')->name('mapa-lote-forming.remove');
    });

    Route::group(['prefix' => 'portal', 'namespace' => 'Portal', 'as'=>'portal.'], function()
    {
        Route::get('mapas', 'MapaController@index')->name('mapas.index');
        Route::get('mapa/{mapa}/produto/{produto}/escolher', 'MapaController@escolher')->name('mapa.escolher');

        Route::post('mapa/{mapa}/produto/{produto}/escolher/mesa/{mesa}', 'MesaController@escolher')->name('mapa.escolher.mesa');

    });

});
