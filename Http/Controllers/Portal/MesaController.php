<?php

namespace Modules\MapaDeMesas\Http\Controllers\Portal;


use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables\Editor\Format;
use Illuminate\Http\Response;
use DataTables\Editor\Options;
use DataTables\Editor\Validate;
use App\FormandoProdutosEServicos;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\MapaDeMesas\Entities\Mesa;
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Services\MapaServices;
use Modules\MapaDeMesas\Entities\MesaEscolhida;

class MesaController extends Controller
{

    public function escolher(Request $request, Mapas $mapa, FormandoProdutosEServicos $produto, Mesa $mesa)
    {

        $forming = auth()->user()->userable;
        $dataMapa = MapaServices::dadosFormandoMapa($forming, $produto, $mapa);
        
        
        if($dataMapa['disponivel'] <= 0){
            $resp = [
                'success' => false,
                'msg' => 'Você já reservou todas as suas mesas disponíveis para esta compra. Caso tenha adquirido outros pacotes de mesa extra que não escolheu ainda, retorne ao menu anterior, e acesse ele para novas escolhas.'
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

        $pag = [];
        $pag['parcelas'] = 0;
        $pag['pago'] = 0;
        foreach ($produto->parcelas as $parcela){

            $date = Carbon::createFromFormat('Y-m-d', $parcela->dt_vencimento);
            if($date->diffInDays(Carbon::now(), false) > 3){
                $pag['parcelas'] += $parcela->valor;

                foreach ($parcela->pagamento as $pagamento){
                    $pag['pago'] += $pagamento->valor_pago;
                }
            }
        }

        if($pag['pago'] < $pag['parcelas']){
            $resp = [
                'success' => false,
                'msg' => 'Constam pagamentos em abertos, favor entre em contato com o Atendimento'
            ];
            return $resp;
        }




        if($mesa->escolhas->count() > 0){
            $resp = [
                'success' => false,
                'msg' => 'Esta mesa já está escolhida por outro formando'
            ];
            return $resp;
        }

        //Verifica a quantidade que essa mesa usa
        if($mesa->config->qtd_mesa > 1){

            // dd($mesa->config->qtd_mesa);

            $formandoTotalMesas = MapaServices::formandoTotalMesas($forming);
            if($formandoTotalMesas['disponivel'] < $mesa->config->qtd_mesa){
                $resp = [
                    'success' => false,
                    'msg' => "Esta mesa precisa utilizar {$mesa->config->qtd_mesa} mesas para escolha e você só possui {$formandoTotalMesas['disponivel']} disponível, favor escolha outra mesa!"
                ];
                return $resp;
            }

            $repeat = filter_input(INPUT_GET, 'repeat', FILTER_VALIDATE_INT);
            $qtdParaEscolha = ($mesa->config->qtd_mesa - 1);
            if(!$repeat){
                $resp = [
                    'success' => true,
                    'repeat' => true,
                    'msg' => "Esta mesa precisa utilizar {$mesa->config->qtd_mesa} mesas para escolha, você confirma utilizar mais {$qtdParaEscolha} mesa(s) para escolher essa?"
                ];
                return $resp;
            }else{

                $fpss = MapaServices::formandoSelecionaMesasFpsId($forming);
                $arrFpsRet = [];
                foreach($fpss['fps_id'] as $vl){

                    $fpsTempPag = FormandoProdutosEServicos::find($vl);

                    foreach ($fpsTempPag->parcelas as $parcela){

                        $date = Carbon::createFromFormat('Y-m-d', $parcela->dt_vencimento);
                        if($date->diffInDays(Carbon::now(), false) > 3){
                            $pag['parcelas'] += $parcela->valor;
            
                            foreach ($parcela->pagamento as $pagamento){
                                $pag['pago'] += $pagamento->valor_pago;
                            }
                        }
                    }
            
                    if($pag['pago'] >= $pag['parcelas']){
                        @$arrFpsRet[$vl]++;
                    }

                    
                }

                if(count($arrFpsRet) < $mesa->config->qtd_mesa){
                    $resp = [
                        'success' => false,
                        'msg' => "Constam pagamentos em abertos em uma das suas mesas que seria utilizado na soma para essa escolha, favor entre em contato com o Atendimento!"
                    ];
                    return $resp;
                }

                $arrFpsUsar = [];
                if($arrFpsRet[$produto->id]){
                    $arrFpsUsar[] = $produto->id;
                    unset($arrFpsRet[$produto->id]);
                    $arrFpsUsarValues = array_keys($arrFpsRet);
                    for($i = 1; $i <= $qtdParaEscolha; $i++){

                        $arrFpsUsar[] = array_shift($arrFpsUsarValues);
                    }

                    try {

                        DB::beginTransaction();

                        foreach($arrFpsUsar as $usar){

                            
                            MesaEscolhida::create([
                                'mesa_id' => $mesa->id,
                                'mapa_id' => $mapa->id,
                                'event_id' => $mapa->event_id,
                                'fps_id' => $usar,
                                'forming_id' => $forming->id
                            ]);

                        }

                        DB::commit();

                        $resp = [
                            'success' => true,
                            'msg' => 'Mesa reservada com sucesso!'
                        ];

                        return $resp;

                    }catch(Exception $e){

                        DB::rollback();

                        $resp = [
                            'success' => false,
                            'msg' => 'Erro ao tentar reservar a mesa: '.$e->getMessage()
                        ];
                        return $resp;

                        
                    }
                    


                }else{
                    $resp = [
                        'success' => false,
                        'msg' => "Erro ao tentar escolher essa mesa [EC: 276]"
                    ];
                }

            }

            

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
