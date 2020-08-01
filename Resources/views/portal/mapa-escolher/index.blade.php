@extends('portal.inc.layout')

@section('content')


    <section class="page-content">
        <div class="page-content-inner">

            <section class="panel">
                <div class="panel-heading">

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-5">

                            <span style="font-size: 24px; font-weight: bold; display: block"><a href="{{route('mapademesas.portal.mapas.index')}}" class="btn btn-default" style="margin: 0 20px 10px 0">VOLTAR</a> Escolha sua mesa</span>
                            <h5>{{$dataMapa['product']['name']}} (#{{$dataMapa['product']['id']}}) </h5>
                            <h6>Evento: {{$dataMapa['event']['name']}} - {{date("d/m/Y H:i", strtotime($dataMapa['event']['date']))}}</h6>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-4" style="border-left: 4px solid green; height: 80px; font-size: 24px; font-weight: bold; padding-left: 10px;">
                                    <span style="color: green">MESAS COMPRADAS</span> <br>
                                    {{$dataMapa['qtMesas']}}
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4" style="border-left: 4px solid red; height: 80px; font-size: 24px; font-weight: bold; padding-left: 10px;">
                                    <span style="color: red">ESCOLHIDAS</span> <br>
                                    {{$dataMapa['escolhidas']}}
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4" style="border-left: 4px solid orange; height: 80px; font-size: 24px; font-weight: bold; padding-left: 10px;">
                                    <span style="color: orange"> DISPONÍVEL</span> <br>
                                    {{$dataMapa['disponivel']}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <section class="panel" style="">
                        <div class="panel-body">
                            <section class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div style="width: {{$dataMapa['mapa']['imagem_x']}}px; height: {{$dataMapa['mapa']['imagem_y']}}px; margin: 10px auto; background: white; position: relative">
                                                <img src="{{asset('uploads/mapa/' . $dataMapa['mapa']['imagem'])}}?rand={{rand((250*250), (1050*1050))}}" style="width: {{$dataMapa['mapa']['imagem_x']}}px; height: {{$dataMapa['mapa']['imagem_y']}}px;">
                                                @foreach($mesas as $mesa)
                                                    <?php
                                                    $popover_title = '';
                                                    $popover_content = '';
                                                    $popover = '';
                                                    $functionReservar = '';

                                                    if($mesa['escolhida'] && count($mesa['escolhas']) > 0){
                                                        $popover = "popover";
                                                        foreach ($mesa['escolhas'] as $escolha){
                                                            $popover_title.= "{$escolha['nome']} {$escolha['sobrenome']} <br>";
                                                            $popover_content.= "<img src=\" ".asset($escolha['img'])." \" style=\"width: 80px;\" > ";
                                                        }
                                                    }elseif ($mesa['mesa']['bloqueada'] == 1){
                                                        $popover = "popover";
                                                        $popover_title.= "Não disponível";
                                                    } else {
                                                        $popover = "popover";
                                                        $popover_title.= "LIVRE";
                                                        $functionReservar = "reservarMesa({$mesa['mesa']['id']})";
                                                    }
                                                    ?>
                                                    <div
                                                            onclick="{{$functionReservar}}"
                                                            data-toggle="{{$popover}}"
                                                            data-title="{{$popover_title}}"
                                                            data-content="{{$popover_content}}"
                                                            style="position: absolute;
                                                                    cursor: pointer;
                                                                    border: 2px {{$mesa['config']['color']}} solid;
                                                                    text-align: center; width: {{$mesa['mesa']['config']['width']}}px;
                                                                    height: {{$mesa['mesa']['config']['height']}}px;
                                                                    border-radius: {{$mesa['mesa']['config']['radius']}}px;
                                                                    line-height: {{$mesa['mesa']['config']['line_height']}}px; font-size: {{$mesa['mesa']['config']['font_size']}}px;
                                                                    background-color: {{$mesa['config']['background_color']}};
                                                                    top: {{$mesa['mesa']['top']}}px; left: {{$mesa['mesa']['left']}}px;
                                                                    z-index: 2;
                                                                    color: {{$mesa['config']['color']}};
                                                                    font-weight: bold;">{{$mesa['mesa']['numero']}}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        var mesasDisponiveis = {{$dataMapa['disponivel']}}

        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                placement: 'top',
                trigger: 'hover'
            })
        })

        function reservarMesa(id){
            if(mesasDisponiveis <= 0){
                Swal.fire(
                    'Erro',
                    'Você já reservou todas as suas mesas disponíveis',
                    'error'
                )
                return false;
            }

            Swal.fire({
                title: 'Você confirma a reserva dessa mesa?',
                text: "Esta ação não poderá ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {

                    fetch(`escolher/mesa/${id}`,{
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data){
                            if(data.success){
                                Swal.fire(
                                    'Parabéns!',
                                    data.msg,
                                    'success'
                                ).then(() => {
                                    document.location.reload(true);
                                })
                            }else{
                                Swal.fire(
                                    'Erro',
                                    data.msg,
                                    'error'
                                )
                            }
                        }
                    })
                }
            })
        }
    </script>

@endsection