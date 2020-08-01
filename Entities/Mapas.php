<?php

namespace Modules\MapaDeMesas\Entities;

use Illuminate\Database\Eloquent\Model;

class Mapas extends Model
{
    protected $fillable = [];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function config()
    {
        return $this->hasOne(MesasTipoConfig::class, 'id', 'config_id');
    }
}
