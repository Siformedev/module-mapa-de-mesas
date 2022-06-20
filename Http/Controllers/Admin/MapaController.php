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
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MapaController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        return view('mapademesas::admin.mapa.index');
    }

    public function datatable()
    {

        $contract_id = filter_input(INPUT_GET, 'contract_id', FILTER_VALIDATE_INT);

        Editor::inst( $this->db, 'mapas', 'id' )
            ->fields(
                Field::inst( 'mapas.id' ),
                Field::inst( 'mapas.event_id' )
                    ->options( Options::inst()
                        ->table( 'events' )
                        ->value( 'id' )
                        ->label( 'name' )
                    )->Validator(Validate::notEmpty()),
                Field::inst( 'mapas.nome' )
                    ->Validator(Validate::notEmpty()),
                Field::inst( 'mapas.data_inicio' )
                    ->getFormatter( Format::dateSqlToFormat( 'd/m/Y H:i' ) )
                    ->setFormatter( Format::dateTime( 'd/m/Y H:i', 'Y-m-d H:i:s' ) )
                    ->Validator(Validate::notEmpty()),
                Field::inst( 'mapas.data_fim' )
                    ->getFormatter( Format::dateSqlToFormat( 'd/m/Y H:i' ) )
                    ->setFormatter( Format::dateTime( 'd/m/Y H:i', 'Y-m-d H:i:s' ) )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mapas.status' )
                    ->Validator(Validate::boolean()),

                /* LEFT JOIN  */
                Field::inst( 'events.name' )
            )
            ->where(function ($q) use ($contract_id){
                if($contract_id){
                    $q->where('mapas.contract_id', $contract_id);
                }

            })
            ->leftJoin('events', 'events.id', '=', 'mapas.event_id')
            ->process( $_POST )
            ->json();
    }
}
