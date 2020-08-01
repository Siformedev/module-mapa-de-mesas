<?php

namespace Modules\MapaDeMesas\Entities;

use Illuminate\Database\Eloquent\Model;

class MesasTipoConfig extends Model
{
    protected $fillable = [
        'nome',
        'width',
        'height',
        'radius',
        'line_height',
        'font_size',
        'background_color_livre',
        'background_color_ocupada',
        'background_color_reversada',
        'color_livre',
        'color_livrcolor_ocupada',
        'color_reversada',
        'active'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
