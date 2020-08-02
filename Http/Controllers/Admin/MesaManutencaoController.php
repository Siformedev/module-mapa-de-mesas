<?php

namespace Modules\MapaDeMesas\Http\Controllers\Admin;


use DataTables\Editor\Format;
use DataTables\Editor\Options;
use DataTables\Editor\Validate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field;
use Illuminate\Support\Facades\DB;
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Entities\Mesa;
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MesaManutencaoController extends Controller
{
    use DatatableTrait;

    public function add(Mapas $id, $top, $left, $config = 0)
    {
        $mapa = $id;
        $numMesa = $this->proximaMesa($mapa);

        $mesa = Mesa::create([
            'mapa_id' => $mapa->id,
            'numero' => $numMesa,
            'top' => $top,
            'left' => $left,
            'config_id' => $config,
            'status' => 1,
        ]);

        $mesa->config;
        return $mesa;
    }

    public function listar(Mapas $id)
    {
        $mapa = $id;
        $mesas = Mesa::with('config')->where('mapa_id', $mapa->id)->orderBy('numero', 'asc')->get();
        $proximaMesa = $this->proximaMesa($mapa);
        return ['mesas' => $mesas, 'proxima' => $proximaMesa];
    }

    public function editTop(Mapas $id, $mesa, $top)
    {
        $mapa = $id;
        $mesa = Mesa::where('mapa_id', $mapa->id)->where('id', $mesa)->first();
        $mesa->top = $top;
        $mesa->save();
        return ['success' => true];
    }

    public function editLeft(Mapas $id, $mesa, $left)
    {
        $mapa = $id;
        $mesa = Mesa::where('mapa_id', $mapa->id)->where('id', $mesa)->first();
        $mesa->left = $left;
        $mesa->save();
        return ['success' => true];
    }

    public function editTopLeft(Mapas $id, $mesa, $top, $left)
    {
        $mapa = $id;
        $mesa = Mesa::where('mapa_id', $mapa->id)->where('id', $mesa)->first();
        $mesa->top = $top;
        $mesa->left = $left;
        $mesa->save();
        return ['success' => true];
    }

    public function del(Mapas $id, $mesa)
    {
        $mapa = $id;
        $mesa = Mesa::where('mapa_id', $mapa->id)->where('id', $mesa)->first();
        $mesa->delete();
        return ['success' => true];
    }

    public function block(Mapas $mapa, Mesa $mesa)
    {
        $bloqueada = ($mesa->bloqueada == 0) ? 1 : 0;
        $mesa->bloqueada = $bloqueada;
        $mesa->save();
        return $mesa;
    }

    private function proximaMesa(Mapas $mapa)
    {
        $ultimaMesa = Mesa::where('mapa_id', $mapa->id)->max('numero');
        $numMesa = $ultimaMesa++;

        $mesas = Mesa::where('mapa_id', $mapa->id)->orderBy('numero')->get(['numero'])->pluck('numero')->toArray();
        for($i=1; $i<=$ultimaMesa; $i++){
            if(!in_array($i, $mesas)){
                $numMesa = $i;
                break;
            }
        }
        return $numMesa;
    }

}
