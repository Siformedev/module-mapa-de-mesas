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
                                        <div class="col-md-8" style="font-size: 16px;">
                                            <span><b>Descrição do Evento:</b> {{$mesa['event']['description']}}</span><br>
                                            <span><b>Produto:</b> {{$mesa['product']['name']}}</span><br>
                                            <span><b>Local:</b> {{$mesa['event']['address']}}</span><br>
                                            <span><b>Você possui {{$mesa['qtMesas']}} mesa (s). E já escolheu {{$mesa['escolhidas']}}</b> </span><br>
                                        </div>
                                        <div class="col-md-3" style="font-size: 16px;">

                                            @if($mesa['disponivel'] <=  0)
                                                <a href="{{route('mapademesas.portal.mapa.escolher', ['mapa' => $mesa['mapa']['id'], 'produto' => $mesa['product']['id']])}}" class="btn btn-info btn-block">MAPA</a>
                                            @else
                                                <a href="{{route('mapademesas.portal.mapa.escolher', ['mapa' => $mesa['mapa']['id'], 'produto' => $mesa['product']['id']])}}" class="btn btn-success btn-block">ESCOLHER</a>
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

@endsection