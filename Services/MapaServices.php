<?php


namespace Modules\MapaDeMesas\Services;


use App\Event;
use App\FormandoProdutosEServicosCateriasTipos;
use Carbon\Carbon;
use Modules\MapaDeMesas\Entities\MapaLoteForming;
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Entities\Mesa;
use Modules\MapaDeMesas\Entities\MesaEscolhida;
use Modules\MapaDeMesas\Entities\MesasTipoConfig;
use phpDocumentor\Reflection\Types\This;

class MapaServices
{

    public static function dadosFormandoProdutosMapa($forming)
    {

        $dataMesas = [];

        foreach($forming->products as $product){

            if($product->status != 1) continue;

            if($product->events_ids != 0){
                $events = explode(',', $product->events_ids);
                foreach ($events as $event){

                    $event = Event::find($event);
                    if($event){
                        $mapas = Mapas::active()->where('event_id', $event->id)->where('data_inicio', '<=', date('Y-m-d H:i:s'))->get();
                        if(count($mapas) > 0){
                            foreach ($mapas as $mapa){

                                $mesa = FormandoProdutosEServicosCateriasTipos::where('fps_id', $product->id)->where('category_id', 2)->where('quantity', '>', 0)->get();
                                $escolhidas = MesaEscolhida::active()
                                    ->where('mapa_id', $mapa->id)
                                    ->where('event_id', $event->id)
                                    ->where('fps_id', $product->id)
                                    ->where('forming_id', $forming->id);

                                $liberacao = MapaLoteForming::where('forming_id', $forming->id)->where('mapa_id', $mapa->id)->first();

                                if($liberacao instanceof MapaLoteForming) $lib = $liberacao->toArray();

                                $qtMesas = $mesa->sum('quantity') * $product->amount;
                                $disponivel = $qtMesas - $escolhidas->count();
                                $escolhidax = $escolhidas->get();
                                $mEscolhidas = [];
                                foreach ($escolhidax as $e){
                                $mEscolhidas[] = $e->mesa->numero;
                                }
                                if(count($mesa) > 0){
                                    $dataMesas[] = [
                                        'product' => $product->toArray(),
                                        'event' => $event->toArray(),
                                        'mapa' => $mapa->toArray(),
                                        'mesas' => $mesa->toArray(),
                                        'qtMesas' => $qtMesas,
                                        'escolhidas' => $escolhidas->count(),
                                        'mesasEscolhidas' => $mEscolhidas,
                                        'disponivel' => $disponivel,
                                        'liberacao' => @$lib,
                                        'liberacaoStatus' => self::verificaLiberacao($liberacao)
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $dataMesas;
    }

    public static function dadosFormandoMapa($forming, $produto, $mapa)
    {

        $dataMesas = null;

        foreach($forming->products as $product){
            if($product->id != $produto->id) continue;

            if($product->events_ids != 0){
                $events = explode(',', $product->events_ids);
                foreach ($events as $event){
                    $event = Event::find($event);
                    if($event){
                        $mapa = Mapas::where('id', $mapa->id)->where('data_inicio', '<=', date('Y-m-d H:i:s'))->first();
                        if($mapa){

                            $mesa = FormandoProdutosEServicosCateriasTipos::where('fps_id', $product->id)->where('category_id', 2)->where('quantity', '>', 0)->get();
                            $escolhidas = MesaEscolhida::active()
                                ->where('mapa_id', $mapa->id)
                                ->where('event_id', $event->id)
                                ->where('fps_id', $product->id)
                                ->where('forming_id', $forming->id);

                            $liberacao = MapaLoteForming::where('forming_id', $forming->id)->where('mapa_id', $mapa->id)->first();

                            if($liberacao instanceof MapaLoteForming) $lib = $liberacao->toArray();

                            $qtMesas = $mesa->sum('quantity') * $product->amount;
                            $disponivel = $qtMesas - $escolhidas->count();
                            $escolhidax = $escolhidas->get();
                            $mEscolhidas = [];
                            foreach ($escolhidax as $e){
                               $mEscolhidas[] = $e->mesa->numero;
                            }
                            if(count($mesa) > 0){
                                $dataMesas = [
                                    'product' => $product->toArray(),
                                    'event' => $event->toArray(),
                                    'mapa' => $mapa->toArray(),
                                    'mesas' => $mesa->toArray(),
                                    'qtMesas' => $qtMesas,
                                    'escolhidas' => $escolhidas->count(),
                                    'mesasEscolhidas' => $mEscolhidas,
                                    'disponivel' => $disponivel,
                                    'liberacao' => @$lib,
                                    'liberacaoStatus' => self::verificaLiberacao($liberacao)
                                ];
                            }

                        }
                    }
                }
            }
        }

        return $dataMesas;
    }

    public static function dadosMesas(Mapas $mapa)
    {
        $dataMesas = [];
        $mesas = Mesa::with(['escolhas', 'config'])->where('mapa_id', $mapa->id)->orderBy('numero')->get();
        foreach ($mesas as $mesa){

            // Escolhas
            $escolhida = false;
            $formandosEscolheu = [];
            if(count($mesa->escolhas) > 0){
                foreach ($mesa->escolhas as $escolha){
                    if($escolha->cancelado == 1) continue;
                    $escolhida = true;
                    $formandosEscolheu[] = $escolha->forming->toArray();
                }
            }

            if($escolhida){
                $config['background_color'] = $mesa->config->background_color_ocupada;
                $config['color'] = $mesa->config->color_ocupada;
            }elseif($mesa->bloqueada){
                $config['background_color'] = $mesa->config->background_color_reversada;
                $config['color'] = $mesa->config->color_reversada;
            }else{
                $config['background_color'] = $mesa->config->background_color_livre;
                $config['color'] = $mesa->config->color_livre;
            }


            $dataMesas[] = [
                'mesa' => $mesa->toArray(),
                'escolhida' => $escolhida,
                'config' => $config,
                'escolhas' => $formandosEscolheu
            ];
        }
        return $dataMesas;
    }

    public static function verificaLiberacao(MapaLoteForming $mapaLoteForming = null)
    {
        if($mapaLoteForming){
            $dateNow = Carbon::now();
            $liberacaoLote = Carbon::createFromFormat('Y-m-d H:i:s', $mapaLoteForming->data_inicio);
            return $dateNow->greaterThanOrEqualTo($liberacaoLote);
        }
        return false;
    }

    public static function formandoTotalMesas($forming)
    {

        $dataMesas = [];

        foreach($forming->products as $product){

            if($product->status != 1) continue;

            if($product->events_ids != 0){
                $events = explode(',', $product->events_ids);
                foreach ($events as $event){

                    $event = Event::find($event);
                    if($event){
                        $mapas = Mapas::active()->where('event_id', $event->id)->where('data_inicio', '<=', date('Y-m-d H:i:s'))->get();
                        if(count($mapas) > 0){
                            foreach ($mapas as $mapa){

                                $mesa = FormandoProdutosEServicosCateriasTipos::where('fps_id', $product->id)->where('category_id', 2)->where('quantity', '>', 0)->get();
                                $escolhidas = MesaEscolhida::active()
                                    ->where('mapa_id', $mapa->id)
                                    ->where('event_id', $event->id)
                                    ->where('fps_id', $product->id)
                                    ->where('forming_id', $forming->id);

                                $liberacao = MapaLoteForming::where('forming_id', $forming->id)->where('mapa_id', $mapa->id)->first();

                                if($liberacao instanceof MapaLoteForming) $lib = $liberacao->toArray();

                                $qtMesas = $mesa->sum('quantity') * $product->amount;
                                $disponivel = $qtMesas - $escolhidas->count();
                                if(count($mesa) > 0){
                                    @$dataMesas['qtMesas']+= $qtMesas;
                                    @$dataMesas['escolhidas']+= $escolhidas->count();
                                    @$dataMesas['disponivel']+= $disponivel;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $dataMesas;
    }

    public static function formandoSelecionaMesasFpsId($forming)
    {

        $dataMesas = [];

        foreach($forming->products as $product){

            if($product->status != 1) continue;

            if($product->events_ids != 0){
                $events = explode(',', $product->events_ids);
                foreach ($events as $event){

                    $event = Event::find($event);
                    if($event){
                        $mapas = Mapas::active()->where('event_id', $event->id)->where('data_inicio', '<=', date('Y-m-d H:i:s'))->get();
                        if(count($mapas) > 0){
                            foreach ($mapas as $mapa){

                                $mesa = FormandoProdutosEServicosCateriasTipos::where('fps_id', $product->id)->where('category_id', 2)->where('quantity', '>', 0)->get();
                                $escolhidas = MesaEscolhida::active()
                                    ->where('mapa_id', $mapa->id)
                                    ->where('event_id', $event->id)
                                    ->where('fps_id', $product->id)
                                    ->where('forming_id', $forming->id);

                                $liberacao = MapaLoteForming::where('forming_id', $forming->id)->where('mapa_id', $mapa->id)->first();

                                if($liberacao instanceof MapaLoteForming) $lib = $liberacao->toArray();

                                $qtMesas = $mesa->sum('quantity') * $product->amount;
                                $disponivel = $qtMesas - $escolhidas->count();
                                if(count($mesa) > 0){
                                    if($disponivel > 0){
                                        for($ii=1;$ii<=$disponivel;$ii++){
                                            @$dataMesas['fps_id'][] = $product->id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $dataMesas;
    }

}