<?php

namespace Modules\MapaDeMesas\Http\Controllers\Admin;


use App\Contract;
use App\FormandoProdutosEServicos;
use App\Forming;
use DataTables\Editor\Format;
use DataTables\Editor\Options;
use DataTables\Editor\Validate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field;
use Illuminate\Support\Facades\DB;
use Modules\MapaDeMesas\Entities\MapaLoteForming;
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MapaLoteFormingController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        return view('mapademesas::admin.mapa-lote-forming.index');
    }

    public function datatable()
    {

        $mapa_id = filter_input(INPUT_GET, 'mapa_id', FILTER_VALIDATE_INT);

        Editor::inst( $this->db, 'mapa_lote_formings', 'id' )
            ->fields(
                Field::inst( 'mapa_lote_formings.id' ),

                Field::inst( 'mapa_lote_formings.mapa_id' )
                    ->options( Options::inst()
                        ->table( 'mapas' )
                        ->value( 'id' )
                        ->label( 'nome' )
                        ->where(function ($q) {
                            $q->where('mapas.status', 1);
                        })
                    )->Validator(Validate::notEmpty()),

                Field::inst( 'mapa_lote_formings.forming_id' )
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

                Field::inst( 'mapa_lote_formings.lote' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mapa_lote_formings.data_inicio' )
                    ->getFormatter( Format::dateSqlToFormat( 'd/m/Y H:i' ) )
                    ->setFormatter( Format::dateTime( 'd/m/Y H:i', 'Y-m-d H:i:s' ) )
                    ->Validator(Validate::notEmpty()),

                /* LEFT JOIN  */
                Field::inst( 'formings.nome' ),
                Field::inst( 'formings.sobrenome' ),
                Field::inst( 'mapas.nome' )
            )
            ->where(function ($q) use ($mapa_id){

                //$q->where('mapa_lote_formings.cancelado', 0);

                if($mapa_id){
                    $q->where('mapa_lote_formings.mapa_id', $mapa_id);
                }

            })
            ->leftJoin('mapas', 'mapas.id', '=', 'mapa_lote_formings.mapa_id')
            ->leftJoin('formings', 'formings.id', '=', 'mapa_lote_formings.forming_id')
            ->process( $_POST )
            ->debug(true)
            ->json();
    }

    public function add()
    {
        return view('mapademesas::admin.mapa-lote-forming-add.index');
    }

    public function addList()
    {
        $idContract     = filter_input(INPUT_GET, 'contract', FILTER_VALIDATE_INT);
        $idMapa         = filter_input(INPUT_GET, 'mapa', FILTER_VALIDATE_INT);

        if(!$idContract || !$idMapa){
            return ['data' => []];
        }
        $dataArray = [];

        $formings = Forming::with('course')->where('contract_id', $idContract)->where('status', 1)->get();
        foreach ($formings as $forming){
            $eventsArray = [];
            $formingArray = [];


            $fps = FormandoProdutosEServicos::with('categorias_tipos')->where('forming_id', $forming->id)->where('status', 1)->get();
            $formingArray['quantity'] = 0;

            foreach ($fps as  $f){


                if(!empty($f->events_ids)){
                    $events = explode(",", $f->events_ids);
                    if(is_array($events) && count($events) > 0){
                        foreach ($events as $key => $event){
                            $eventsArray[$event] = true;
                        }
                    }
                }
                $mapas = Mapas::whereIn('event_id', array_keys($eventsArray))->where('id', $idMapa)->get();


                if($mapas->count()){


                    foreach ($f->categorias_tipos as $cat){
                        if($cat->category_id == 2 && $cat->quantity > 0){
                            $formingArray['quantity']+= $cat->quantity;
                        }
                    }
                }


            }// endforeach

            $lote = MapaLoteForming::where('mapa_id', $idMapa)->where('forming_id', $forming->id)->first();

            if($formingArray['quantity'] > 0){
                $dataArray[] = [
                    'forming' => $forming,
                    'quantity' => $formingArray['quantity'],
                    'lote' => [
                        'numero' => @$lote->lote,
                        'data_inicio' => @$lote->data_inicio
                    ]
                ];
            }



        }
        return ['data' => $dataArray];




//        $res = DB::table('mapa_lote_formings')->rightJoin('formings', 'formings.id', '=', 'mapa_lote_formings.forming_id')->get()->toArray();

    }

    public function getMapasFromContract(Contract $contract)
    {
        $eventsArray = [];
        $fps = FormandoProdutosEServicos::where('contract_id', $contract->id)->where('status', 1)->get();
        foreach ($fps as $f){
            if(!empty($f->events_ids)){
                $events = explode(",", $f->events_ids);
                if(is_array($events) && count($events) > 0){
                    foreach ($events as $key => $event){
                        $eventsArray[$event] = true;
                    }
                }
            }
        }

        $mapas = Mapas::whereIn('event_id', array_keys($eventsArray))->get()->toArray();
        return $mapas;

    }

    public function updateOrCreate(Request $request, Contract $contract, Mapas $mapa)
    {
        $dataPost = $request->only(['lote', 'data_inicio', 'ids']);
        $ids = explode(",", $dataPost['ids']);
        try{
            foreach ($ids as $id){
                $mesaLoteForming = MapaLoteForming::updateOrCreate(['mapa_id' => $mapa->id, 'forming_id' => $id], [
                    'lote' => $dataPost['lote'],
                    'data_inicio' => $dataPost['data_inicio']
                ]);
            }
            return ['success' => true, 'mgs' => 'Adicionados com sucesso!'];
        }catch (Exception $e){
            return ['success' => true, 'msg' => $e->getMessage()];
        }

    }

    public function remove(Request $request, Contract $contract, Mapas $mapa)
    {
        $dataPost = $request->all();
        $ids = explode(",", $dataPost['ids']);
        try{
            foreach ($ids as $id){
                $mesaLoteForming = MapaLoteForming::where(['mapa_id' => $mapa->id, 'forming_id' => $id]);
                if($mesaLoteForming->count()) $mesaLoteForming->delete();
            }
            return ['success' => true, 'mgs' => 'Removidos com sucesso!'];
        }catch (Exception $e){
            return ['success' => true, 'msg' => $e->getMessage()];
        }

    }
}
