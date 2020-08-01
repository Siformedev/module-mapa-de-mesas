<?php

namespace Modules\MapaDeMesas\Entities;

use Illuminate\Database\Eloquent\Model;

class MapaLoteForming extends Model
{
    protected $fillable = [
        'mapa_id',
        'forming_id',
        'lote',
        'data_inicio'
    ];
}
