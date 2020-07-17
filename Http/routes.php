<?php

Route::group(['middleware' => 'web', 'prefix' => 'mapademesas', 'namespace' => 'Modules\MapaDeMesas\Http\Controllers'], function()
{
    Route::get('/', 'MapaDeMesasController@index');
});
