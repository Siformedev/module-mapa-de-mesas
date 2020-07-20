@extends('gerencial.inc.layout')

@section('content')

    <section class="page-content">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{$mapa->nome}}</h1>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Imagem</label>
                            <input class="form-control" type="file" name="imagem" id="imagem">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Config Mesa</label>
                            {!! Form::select('size', \Modules\MapaDeMesas\Entities\MesasTipoConfig::all()->pluck('nome', 'id'), $mapa->config_id, ['placeholder' => 'Selecione a configuração das mesas', 'class' => 'form-control editConfig']); !!}
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Largura (px)</label>
                            <input class="form-control" type="number" name="imagem_x" id="imagem_x" value="{{$mapa->imagem_x}}">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Altura (px)</label>
                            <input class="form-control" type="number" name="imagem_y" id="imagem_y" value="{{$mapa->imagem_y}}">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px">
                        <div id="mydiv" style="position: absolute; width: 450px; height: 600px; z-index: 9; background: white; padding: 10px; border-radius: 10px; border: 2px solid grey;">
                            <div id="mydivheader" style="cursor: move; text-align: center; font-size: 15px; font-weight: bold; color: #494949; display: block; z-index: 10; padding-bottom: 10px">CIQUE E MOVA</div>
                            <div>
                                <div class="row text-center" style="margin-bottom: 10px">
                                    <button class="btn btn-success btn-block" id="addMesa">NOVA MESA</button>
                                </div>
                                <div class="row text-center" style="border-bottom: 1px solid grey; margin-bottom: 15px">
                                    <div class="col-md-2"><span>MESA</span></div>
                                    <div class="col-md-4">TOP</div>
                                    <div class="col-md-4">LEF</div>
                                    <div class="col-md-2">#</div>
                                </div>
                            </div>
                            <div class="containerMesas" style="overflow-y: scroll; overflow-x: hidden; z-index: 100; height: 450px;">
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div id="mapa" style="background: #0e90d2; margin: 0 auto; position: relative">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script type="text/javascript">

        var mesas = [];
        const publicPath = '{{asset('')}}';
        const input = document.getElementById('imagem')
        var imagem_x = '{{$mapa->imagem_x}}';
        var imagem_y = '{{$mapa->imagem_y}}';

        var config = {
            width: '{{$mapa->config->width}}',
            height: '{{$mapa->config->height}}',
            radius: '{{$mapa->config->radius}}',
            line_height: '{{$mapa->config->line_height}}',
            font_size: '{{$mapa->config->font_size}}',
            background_color_livre: '{{$mapa->config->background_color_livre}}',
            background_color_ocupada: '{{$mapa->config->background_color_ocupada}}',
            background_color_reversada: '{{$mapa->config->background_color_reversada}}',
            color_livre: '{{$mapa->config->color_livre}}',
            color_ocupada: '{{$mapa->config->color_ocupada}}',
            color_reversada: '{{$mapa->config->color_reversada}}'
        };

        // This will upload the file after having read it
        const upload = (file) => {
            data = new FormData();
            data.append('imagem', file);

            fetch('manutencao/upload', { // Your POST endpoint
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: data // This is your file object
            }).then(
                response => response.json() // if the response is a JSON object
            ).then(
                success => console.log(success) // Handle the success response object
            ).catch(
                error => console.log(error) // Handle the error response object
            );
        };

        // Event handler executed when a file is selected
        const onSelectFile = () => upload(input.files[0]);

        // Add a listener on your input
        // It will be triggered when a file will be selected
        input.addEventListener('change', onSelectFile, false);

        $(document).ready(function() {
            $('#imagem_x, #imagem_y').change(e => {
                imagem_x = $('#imagem_x').val();
                imagem_y = $('#imagem_y').val();

                editXY(imagem_x, imagem_y);
            })

            $("#addMesa").click(() => {
                fetch('manutencao/mesa/add')
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            getMesas();
                        }
                    })
            })

            $(".containerMesas").on('change', '.mesaTop', function (e){
                id = $(e.target).data('numero');
                top = e.target.value;
                editTop(id, e.target.value);

            });

            $(".containerMesas").on('change', '.mesaLeft', function (e){
                let id = $(e.target).data('numero');
                let top = e.target.value;
                editLeft(id, top);

            })

            $(".editConfig").change(function (e){
                let id = e.target.value;
                editConfig(id);

            })

            getMesas();
            editXY({{$mapa->imagem_x}}, {{$mapa->imagem_y}});
        } );

        function getMesas(){
            fetch('manutencao/mesa/listar')
                .then(res => res.json())
                .then(data => {
                    mesas = data;
                    renderMesas();
                    renderMapaMesas();
                })
        }

        function getMapaMesas(){
            fetch('manutencao/mesa/listar')
                .then(res => res.json())
                .then(data => {
                    mesas = data;
                    renderMapaMesas();
                })
        }

        function renderMesas(){
            $('.containerMesas').html('');
            mesas.forEach((element) => {
                const btnBlockIco = (element.bloqueada == 1) ? 'glyphicon-ban-circle' : 'glyphicon-lock';
                const btnBlockColor = (element.bloqueada == 1) ? 'btn-warning' : 'btn-default';

                let color = config.color_livre;
                let bgColor = config.background_color_livre;

                if(element.bloqueada == 1){
                    color = config.color_reversada;
                    bgColor = config.background_color_reversada;
                }

                $('.containerMesas').append(`
                    <div class="divMesa" style="border: 1px solid lightgrey; border-radius: 10px; padding: 5px">
                        <div class="row">
                            <div class="col-md-2" style="line-height: 35px; font-weight: bold; text-align: center"><div style="cursor: pointer; border: 2px ${color} solid; text-align: center; width: ${config.width}px; height: ${config.height}px; border-radius: ${config.radius}px; line-height: ${config.line_height}px; font-size: ${config.font_size}px; background-color: ${bgColor}; color: ${color}; font-weight: bold; margin: 0 auto">${element.numero}</div></div>
                            <div class="col-md-3"><input data-numero="${element.id}" class="form-control mesaTop" type="number" name="top" value="${element.top}"></div>
                            <div class="col-md-3"><input data-numero="${element.id}" class="form-control mesaLeft" type="number" name="left" value="${element.left}"></div>
                            <div class="col-md-4"><button data-numero="${element.id}" class="btn btn-danger btn-mini" id="mesaDel_${element.id}" onclick="mesaDel(${element.id})">X</button> <button data-numero="${element.id}" class="btn ${btnBlockColor} btn-mini" id="mesaBlock_${element.id}" onclick="mesaBlock(${element.id})"><i class="glyphicon ${btnBlockIco}"></i></button></div>
                        </div>
                    </div>
                `);
            })
        }

        function renderMapaMesas(){
            const rand = Math.random() * (9999999 - 1111) + 1111;
            $("#mapa").html(`<img src="${publicPath}/uploads/mapa/{{$mapa->id}}.jpg?v=${rand}" style="width: ${imagem_x}px; height: ${imagem_y}px; z-index:1" />`);
            mesas.forEach((element) => {

                let color = config.color_livre;
                let bgColor = config.background_color_livre;

                if(element.bloqueada == 1){
                    color = config.color_reversada;
                    bgColor = config.background_color_reversada;
                }

                $("#mapa").append(`
                    <div style="position: absolute; cursor: pointer; border: 2px ${config.color_livre} solid; text-align: center; width: ${config.width}px; height: ${config.height}px; border-radius: ${config.radius}px; line-height: ${config.line_height}px; font-size: ${config.font_size}px; background-color: ${bgColor}; top: ${element.top}px; left: ${element.left}px; z-index: 2; color: ${color}; font-weight: bold;">${element.numero}</div>
                `);
            })
        }

        function editXY(x, y){
            fetch(`manutencao/editXY/${x}/${y}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).then(
                response => response.json() // if the response is a JSON object
            ).then(data => {
                if(data.success){
                    const rand = Math.random() * (9999999 - 1111) + 1111;
                    $("#mapa").css('width', x).css('height', y);
                    $("#mapa").html();
                    $("#mapa").html(`<img src="${publicPath}/uploads/mapa/{{$mapa->id}}.jpg?v=${rand}" style="width: ${x}px; height: ${y}px; z-index:1" />`);

                }}  // Handle the success response object
            ).catch(
                error => console.log(error) // Handle the error response object
            );
        }

        function editTop(id, top){
            fetch(`manutencao/mesa/edit-top/${id}/${top}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        getMapaMesas();
                    }
                })
        }

        function editLeft(id, left){
            fetch(`manutencao/mesa/edit-left/${id}/${left}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        getMapaMesas();
                    }
                })
        }

        function mesaDel(id){
            fetch(`manutencao/mesa/del/${id}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        getMesas();
                    }
                })
        }

        function mesaBlock(id){
            fetch(`manutencao/mesa/block/${id}`,{
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
                .then(res => res.json())
                .then(data => {
                    if(data){
                        if(data.bloqueada == 1){
                            $(`#mesaBlock_${id}`).addClass('btn-warning').removeClass('btn-default');
                            $(`#mesaBlock_${id} > i`).removeClass('glyphicon-lock').addClass('glyphicon-ban-circle');
                        }else{
                            $(`#mesaBlock_${id}`).removeClass('btn-warning').addClass('btn-default');
                            $(`#mesaBlock_${id} > i`).addClass('glyphicon-lock').removeClass('glyphicon-ban-circle');
                        }
                        getMesas();
                    }
                })
        }

        function editConfig(id){
            fetch(`manutencao/edit-config/${id}`,{
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
                .then(res => res.json())
                .then(data => {
                    if(data){
                        config = data;
                        getMesas();
                    }
                })
        }




        // Make the DIV element draggable:
        dragElement(document.getElementById("mydiv"));

        function dragElement(elmnt) {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(elmnt.id + "header")) {
                // if present, the header is where you move the DIV from:
                document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }
    </script>


@endsection

@section('style')

@endsection