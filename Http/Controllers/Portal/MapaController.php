<?php

namespace Modules\MapaDeMesas\Http\Controllers\Portal;


use App\Event;
use App\FormandoProdutosEServicos;
use App\FormandoProdutosEServicosCateriasTipos;
use Carbon\Carbon;
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
use Modules\MapaDeMesas\Entities\MesaEscolhida;
use Modules\MapaDeMesas\Services\MapaServices;

class MapaController extends Controller
{
    public function index()
    {
        $forming = auth()->user()->userable;

        $dataMesas = MapaServices::dadosFormandoProdutosMapa($forming);

        return view('mapademesas::portal.mapas.index', compact('dataMesas'));
    }

    public function escolher(Mapas $mapa, FormandoProdutosEServicos $produto)
    {
        $forming = auth()->user()->userable;

        $dataMapa = MapaServices::dadosFormandoMapa($forming, $produto, $mapa);
        $formandoTotalMesas = MapaServices::formandoTotalMesas($forming);
        if(!$dataMapa['liberacaoStatus']){
            return redirect()->route('mapademesas.portal.mapas.index');
        }

        $mesas = MapaServices::dadosMesas($mapa);

        return view('mapademesas::portal.mapa-escolher.index', compact('dataMapa', 'mesas', 'formandoTotalMesas'));
    }


}
