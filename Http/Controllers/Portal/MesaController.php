<?php

namespace Modules\MapaDeMesas\Http\Controllers\Portal;


use App\FormandoProdutosEServicos;
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
use Modules\MapaDeMesas\Entities\MesaEscolhida;
use Modules\MapaDeMesas\Services\MapaServices;

class MesaController extends Controller
{

    public function escolher(Request $request, Mapas $mapa, FormandoProdutosEServicos $produto, Mesa $mesa)
    {

        $forming = auth()->user()->userable;
        $dataMapa = MapaServices::dadosFormandoMapa($forming, $produto, $mapa);
        if($dataMapa['disponivel'] <= 0){
            $resp = [
                'success' => false,
                'msg' => 'Você já reservou todas suas mesas'
            ];
            return $resp;
        }

        $resp = [];
        if($mesa->escolhas->count() > 0){
            $resp = [
                'success' => false,
                'msg' => 'Esta mesa já está escolhida por outro formando'
            ];
            return $resp;
        }

        $escolha = MesaEscolhida::create([
            'mesa_id' => $mesa->id,
            'mapa_id' => $mapa->id,
            'event_id' => $mapa->event_id,
            'fps_id' => $produto->id,
            'forming_id' => $forming->id
        ]);

        if($escolha){
            $resp = [
                'success' => true,
                'msg' => 'Mesa reservada com sucesso!'
            ];
            return $resp;
        }

        $resp = [
            'success' => false,
            'msg' => 'Erro ao tentar reservar a mesa'
        ];
        return $resp;
    }

}
