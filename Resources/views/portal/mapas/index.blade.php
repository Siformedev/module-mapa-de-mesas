@extends('portal.inc.layout')

@section('content')


    <section class="page-content">
        <div class="page-content-inner">

            <section class="panel">
                <div class="panel-heading">

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Mapas De Mesas</h2>
                            <span>Abaixo estão os mapas disponiveis para escolha das mesa (s)</span>
                        </div>
                    </div>
                </div>
            </section>

            @foreach($dataMesas as $mesa)
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <section class="panel" style="">
                        <div class="panel-body">


                            <section class="panel">
                                <div class="panel-heading">
                                    <h3>
                                        <a href="#">{{$mesa['event']['name']}}
                                            - {{date("d/m/Y H:i", strtotime($mesa['event']['date']))}}</a>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <span style="font-size: 50px"><i class="icmn-map3"></i></span>
                                        </div>
                                        <div class="col-md-7" style="font-size: 16px;">
                                            <span><b>Descrição do Evento:</b> {{$mesa['event']['description']}}</span><br>
                                            <span><b>Produto:</b> {{$mesa['product']['name']}}</span><br>
                                            <span><b>Local:</b> {{$mesa['event']['address']}}</span><br>
                                            <span><b>Você possui {{$mesa['qtMesas']}} mesa (s). E já escolheu {{($mesa['escolhidas'] >= $mesa['qtMesas']) ? 'todas' : $mesa['escolhidas']}}</b> </span><br>
                                        </div>
                                        <div class="col-md-4" style="font-size: 16px;">
                                            @if($mesa['liberacaoStatus'])

                                                @if($mesa['disponivel'] <=  0)
                                                    <a href="{{route('mapademesas.portal.mapa.escolher', ['mapa' => $mesa['mapa']['id'], 'produto' => $mesa['product']['id']])}}" class="btn btn-info btn-block">MAPA</a>
                                                @else
                                                    <a href="{{route('mapademesas.portal.mapa.escolher', ['mapa' => $mesa['mapa']['id'], 'produto' => $mesa['product']['id']])}}" class="btn btn-success btn-block">ESCOLHER</a>
                                                @endif
                                            @else

                                                @if(isset($mesa['liberacao']) && is_array($mesa['liberacao']) && count($mesa['liberacao']) > 0)
                                                    <?php
                                                    $clock = [
                                                        'id' => $mesa['product']['id'],
                                                        'date' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mesa['liberacao']['data_inicio'])->format('Y/m/d H:i:s')
                                                    ];
                                                    $clocks[] = $clock;
                                                    ?>
                                                    <div style="border: 2px dashed grey; padding: 15px; border-radius: 5px; text-align: center">
                                                        Sua mesa sera liberada para escolha dia <span class="label label-info">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mesa['liberacao']['data_inicio'])->format('d/m/Y H:i')}}</span>
                                                        <hr><span class="label label-success" style="display: block"> <div id="clock_{{$clock['id']}}"></div></span>
                                                    </div>
                                                @else
                                                    <div style="border: 2px dashed grey; padding: 15px; border-radius: 5px; text-align: center">Em breve será divulgado a data de liberação para escolha da sua mesa</div>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                    </section>
                </div>
            @endforeach


        </div>
    </section>

    <script src="{{asset('js/jquery.countdown.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function (){
            let clocks = [];
            @if(isset($clocks))
                @foreach($clocks as $clock)
                let stuff = '{!! json_encode($clock) !!}';
                let clock = JSON.parse(stuff)
                clocks.push(clock);

                $('#clock_{{$clock['id']}}').countdown('{{$clock['date']}}', function(event) {

                    if(parseInt(event.offset.totalDays) > 0){
                        var $this = $(this).html(event.strftime('Faltam: <b>%D Dia(s) %H Hora(s) %M Minuto(s) %S Segundo(s)<b>'));
                    }else{
                        var $this = $(this).html(event.strftime('Faltam: <b>%H Hora(s) %M Minuto(s) %S Segundo(s)<b>'));
                    }
                }).on('finish.countdown', function(event) {
                    setTimeout(() => {
                        document.location.reload(true);
                    },  1000)

                });

                @endforeach
            @endif

        });
    </script>

@endsection