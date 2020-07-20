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
use Modules\MapaDeMesas\Entities\mapas;
use Modules\MapaDeMesas\Entities\Mesa;

class MesaManutencaoController extends Controller
{

    public function add(Mapas $id)
    {
        $mapa = $id;
        $ultimaMesa = Mesa::where('mapa_id', $mapa->id)->max('numero');
        $numMesa = $ultimaMesa + 1;
        Mesa::create([
            'mapa_id' => $mapa->id,
            'numero' => $numMesa,
            'top' => rand(10, 30),
            'left' => rand(10, 30),
            'status' => 1,
        ]);

        return ['success' => true];
    }

    public function listar(Mapas $id)
    {
        $mapa = $id;
        $mesas = Mesa::where('mapa_id', $mapa->id)->get();
        return $mesas;
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

}