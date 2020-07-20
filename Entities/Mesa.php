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
    ];

    public function escolhas()
    {
        return $this->hasMany(MesaEscolhida::class, 'mesa_id', 'id');
    }
}
