<?php

namespace Modules\MapaDeMesas\Entities;

use App\Forming;
use Illuminate\Database\Eloquent\Model;

class MesaEscolhida extends Model
{
    protected $fillable = [
        'mesa_id',
        'mapa_id',
        'event_id',
        'forming_id',
        'cancelado',
        'fps_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('cancelado', 0);
    }

    public function forming()
    {
        return $this->belongsTo(Forming::class, 'forming_id', 'id');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }
}
