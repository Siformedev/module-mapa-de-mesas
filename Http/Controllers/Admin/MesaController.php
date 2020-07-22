<?php

namespace Modules\MapaDeMesas\Http\Controllers\Admin;


use App\Forming;
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
use Modules\MapaDeMesas\Services\MapaServices;

class MesaController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        return view('mapademesas::admin.mesa.index');
    }

    public function datatable()
    {

        $mapa_id = filter_input(INPUT_GET, 'mapa_id', FILTER_VALIDATE_INT);

        Editor::inst( $this->db, 'mesas', 'id' )
            ->fields(
                Field::inst( 'mesas.id' ),
                Field::inst( 'mesas.numero' ),
                Field::inst( 'mesas.mapa_id' )
                    ->options( Options::inst()
                        ->table( 'mapas' )
                        ->value( 'id' )
                        ->label( 'nome' )
                        ->where(function ($q) {
                            $q->where('mapas.status', 1);
                        })
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mesas.config_id' )
                    ->options( Options::inst()
                        ->table( 'mesas_tipo_configs' )
                        ->value( 'id' )
                        ->label( 'nome' )
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mesas.top' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas.left' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas.bloqueada' )
                    ->Validator(Validate::boolean())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas.status' )
                    ->Validator(Validate::boolean())
                    ->Validator(Validate::notEmpty()),

                /* LEFT JOIN  */
                Field::inst( 'mapas.nome' ),
                Field::inst( 'mesas_tipo_configs.nome' )

            )
            ->where(function ($q) use ($mapa_id){
                if($mapa_id){
                    $q->where('mesas.mapa_id', $mapa_id);
                }

            })
            ->leftJoin('mapas', 'mapas.id', '=', 'mesas.mapa_id')
            ->leftJoin('mesas_tipo_configs', 'mesas_tipo_configs.id', '=', 'mesas.config_id')
            ->process( $_POST )
            ->debug(true)
            ->json();
    }

    public function listar(Mapas $mapa)
    {
        return Mesa::where('mapa_id', $mapa->id)->orderBy('numero', 'asc')->get();
    }

    public function fpsForming(Mapas $mapa, Forming $forming)
    {
        $infos = MapaServices::dadosFormandoProdutosMapa($forming);
        $resp = [];

        foreach($infos as $info){
            if($info['mapa']['id'] == $mapa->id){
                $resp[] = [
                    'id' => $info['product']['id'],
                    'name' => $info['product']['name']. ' [#' . $info['product']['id'] . ']',
                    'disponivel' => $info['disponivel']
                ];
            }
        }
        return $resp;
    }
}
