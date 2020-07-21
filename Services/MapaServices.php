<?php


namespace Modules\MapaDeMesas\Services;


use App\Event;
use App\FormandoProdutosEServicosCateriasTipos;
use Modules\MapaDeMesas\Entities\Mapas;
use Modules\MapaDeMesas\Entities\Mesa;
use Modules\MapaDeMesas\Entities\MesaEscolhida;
use Modules\MapaDeMesas\Entities\MesasTipoConfig;

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

                                $qtMesas = $mesa->sum('quantity');
                                $disponivel = $qtMesas - $escolhidas->count();
                                if(count($mesa) > 0){
                                    $dataMesas[] = [
                                        'product' => $product->toArray(),
                                        'event' => $event->toArray(),
                                        'mapa' => $mapa->toArray(),
                                        'mesas' => $mesa->toArray(),
                                        'qtMesas' => $qtMesas,
                                        'escolhidas' => $escolhidas->count(),
                                        'disponivel' => $disponivel
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

                            $qtMesas = $mesa->sum('quantity');
                            $disponivel = $qtMesas - $escolhidas->count();
                            if(count($mesa) > 0){
                                $dataMesas = [
                                    'product' => $product->toArray(),
                                    'event' => $event->toArray(),
                                    'mapa' => $mapa->toArray(),
                                    'mesas' => $mesa->toArray(),
                                    'qtMesas' => $qtMesas,
                                    'escolhidas' => $escolhidas->count(),
                                    'disponivel' => $disponivel
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
        $mesas = Mesa::with('escolhas')->where('mapa_id', $mapa->id)->orderBy('numero')->get();
        $config = $mapa->config;
        foreach ($mesas as $mesa){

            // Escolhas
            $escolhida = false;
            $formandosEscolheu = [];
            if(count($mesa->escolhas) > 0){
                foreach ($mesa->escolhas as $escolha){
                    if($escolha->cancelado == 1) continue;
                    $escolhida = true;
                    $formandosEscolheu[] = $escolha->forming->first(['id', 'nome', 'sobrenome', 'img'])->toArray();
                }
            }

            $config = [
                'width' => $mapa->config->width,
                'height' => $mapa->config->height,
                'radius' => $mapa->config->radius,
                'line_height' => $mapa->config->line_height,
                'font_size' => $mapa->config->font_size,
            ];

            if($escolhida){
                $config['background_color'] = $mapa->config->background_color_ocupada;
                $config['color'] = $mapa->config->color_ocupada;
            }elseif($mesa->bloqueada){
                $config['background_color'] = $mapa->config->background_color_reversada;
                $config['color'] = $mapa->config->color_reversada;
            }else{
                $config['background_color'] = $mapa->config->background_color_livre;
                $config['color'] = $mapa->config->color_livre;
            }


            $dataMesas[] = [
                'mesa' => $mesa->toArray(),
                'config' => $config,
                'escolhida' => $escolhida,
                'escolhas' => $formandosEscolheu
            ];
        }
        return $dataMesas;
    }

}