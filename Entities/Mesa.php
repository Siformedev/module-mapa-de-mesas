<?php

namespace Modules\MapaDeMesas\Entities;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = [
        'mapa_id',
        'numero',
        'top',
        'left',
        'status',
        'config_id',
    ];

    public function escolhas()
    {
        return $this->hasMany(MesaEscolhida::class, 'mesa_id', 'id');
    }

    public function config()
    {
        return $this->hasOne(MesasTipoConfig::class, 'id', 'config_id');
    }
}
