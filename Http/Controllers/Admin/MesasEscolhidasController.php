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
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MesasEscolhidasController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        return view('mapademesas::admin.mesas-escolhidas.index');
    }

    public function datatable()
    {

        $mapa_id = filter_input(INPUT_GET, 'mapa_id', FILTER_VALIDATE_INT);

        Editor::inst( $this->db, 'mesa_escolhidas', 'id' )
            ->fields(
                Field::inst( 'mesa_escolhidas.id' ),

                Field::inst( 'mesa_escolhidas.mesa_id' )
//                    ->options( Options::inst()
//                        ->table( 'mesas' )
//                        ->value( 'id' )
//                        ->label( 'numero' )
//                        ->where(function ($q) {
//                            $q->where('mesas.status', 1);
//                        })
//                    )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesa_escolhidas.mapa_id' )
                    ->options( Options::inst()
                        ->table( 'mapas' )
                        ->value( 'id' )
                        ->label( 'nome' )
                        ->where(function ($q) {
                            $q->where('mapas.status', 1);
                        })
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mesa_escolhidas.event_id' )
                    ->options( Options::inst()
                        ->table( 'events' )
                        ->value( 'id' )
                        ->label( 'name' )
                        ->where(function ($q) {
                            $q->where('events.status', 1);
                        })
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mesa_escolhidas.forming_id' )
                    ->options( Options::inst()
                        ->table( 'formings' )
                        ->value( 'id' )
                        ->label( ['nome', 'sobrenome'] )
                        ->render(function ($d){
                            return $d['nome'] . " " . $d['sobrenome'];
                        })
                        ->where(function ($q) {
                            $q->where('formings.status', 1);
                        })
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mesa_escolhidas.fps_id' )
//                    ->options( Options::inst()
//                        ->table( 'formando_produtos_e_servicos' )
//                        ->value( 'id' )
//                        ->label( 'name')
//                        ->where(function ($q) {
//                            $q->where('formando_produtos_e_servicos.status', 1);
//                        })
//                    )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesa_escolhidas.cancelado' )
                    ->Validator(Validate::boolean())
                    ->Validator(Validate::notEmpty()),

                /* LEFT JOIN  */
                Field::inst( 'mesas.numero' ),
                Field::inst( 'events.name' ),
                Field::inst( 'formings.nome' ),
                Field::inst( 'formings.sobrenome' ),
                Field::inst( 'formando_produtos_e_servicos.name' ),
                Field::inst( 'mapas.nome' )
            )
            ->where(function ($q) use ($mapa_id){

                //$q->where('mesa_escolhidas.cancelado', 0);

                if($mapa_id){
                    $q->where('mesa_escolhidas.mapa_id', $mapa_id);
                }

            })
            ->leftJoin('mesas', 'mesas.id', '=', 'mesa_escolhidas.mesa_id')
            ->leftJoin('mapas', 'mapas.id', '=', 'mesa_escolhidas.mapa_id')
            ->leftJoin('events', 'events.id', '=', 'mesa_escolhidas.event_id')
            ->leftJoin('formings', 'formings.id', '=', 'mesa_escolhidas.forming_id')
            ->leftJoin('formando_produtos_e_servicos', 'formando_produtos_e_servicos.id', '=', 'mesa_escolhidas.fps_id')
            ->on('preCreate', function ($e, &$d){
                $mapa = Mapas::find($d['mesa_escolhidas']['mapa_id']);
                $d['mesa_escolhidas']['event_id'] = $mapa->event_id;
                return $d;
            })
            ->on('preEdit', function ($e, $i, &$d){
                $mapa = Mapas::find($d['mesa_escolhidas']['mapa_id']);
                $d['mesa_escolhidas']['event_id'] = $mapa->event_id;
                return $d;
            })
            ->process( $_POST )
            ->debug(true)
            ->json();
    }
}
