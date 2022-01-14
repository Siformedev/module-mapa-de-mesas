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

                        <div class="col-md-2 form-group">
                            <label>Config Mesa</label>
                            {!! Form::select('size', \Modules\MapaDeMesas\Entities\MesasTipoConfig::all()->pluck('nome', 'id'), null, ['placeholder' => 'Selecione a configuração das mesas', 'class' => 'form-control editConfig']); !!}
                        </div>

                        <div class="col-md-1 form-group">
                            <label> &nbsp; </label>
                            <button class="form-control btn btn-info" onclick="editarConfig()"><i class="icmn-pencil"></i></button>
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


                        <div class="panel panel-default" id="mydiv" style="position: absolute; width: 450px; max-height: 680px; z-index: 9; border-radius: 10px;">
                            <div class="panel-heading active" role="tab" id="headingOne">
                                <span class="panel-title" id="mydivheader" style="cursor: move">
                                    CLIQUE E MOVA
                                </span>
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                                    </a>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div id="mesas_config" style="margin-bottom: 10px; white-space: nowrap; overflow: auto; height: 80px; width: 100%;">
                                        <img src="{{asset('img/loading.gif')}}" style="width: 150px; margin: 0 auto;">
                                    </div>
                                    <hr>
                                    {{--                                <div class="row text-center" style="margin-bottom: 10px">--}}
                                    {{--                                    <button class="btn btn-success btn-block" id="addMesa">NOVA MESA</button>--}}
                                    {{--                                </div>--}}

                                    <div class="row text-center" style="border-bottom: 1px solid grey; margin-bottom: 15px">
                                        <div class="col-md-2"><span>MESA</span></div>
                                        <div class="col-md-4">TOP</div>
                                        <div class="col-md-4">LEF</div>
                                        <div class="col-md-2">#</div>
                                    </div>

                                    <div class="containerMesas" style="overflow-y: scroll; overflow-x: hidden; z-index: 100; height: 450px;">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 form-group" style="overflow-x: auto;">
                            <div id="mapa" class="dropzone" style="width: {{$mapa->imagem_x}}px; height: {{$mapa->imagem_y}}px; margin: 0 auto; position: relative;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

    <script type="text/javascript">

        var mesas = [];
        var mesasConfig = [];
        const publicPath = '{{asset('')}}';
        const input = document.getElementById('imagem')
        var imagem_x = '{{$mapa->imagem_x}}';
        var imagem_y = '{{$mapa->imagem_y}}';
        var proximaNumero = 0;



        // This will upload the file after having read it
        const upload = (file) => {
            data = new FormData();
            data.append('imagem', file);

            fetch('manutencao/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: data
            }).then(
                response => response.json()
            ).then(
                success => {
                    window.location.reload(true);

                {{--const rand = Math.random() * (9999999 - 1111) + 1111;--}}
                    {{--$("#imagem_x").val(success.x);--}}
                    {{--$("#imagem_y").val(success.y);--}}
                    {{--imagem_x = success.x;--}}
                    {{--imagem_y = success.y;--}}
                    {{--$("#mapa").css('width', success.x).css('height', success.y);--}}
                    {{--$("#mapa").html();--}}
                    {{--$("#mapa").html(`<img src="${publicPath}/uploads/mapa/{{$mapa->id}}.jpg?v=${rand}" style="width: ${success.x}px; height: ${success.y}px; z-index:1" />`);--}}
                }
            ).catch(
                error => console.log(error)
            );
        };

        const onSelectFile = () => upload(input.files[0]);

        input.addEventListener('change', onSelectFile, false);

        $(document).ready(function() {
            $('#imagem_x, #imagem_y').change(e => {
                imagem_x = $('#imagem_x').val();
                imagem_y = $('#imagem_y').val();

                editXY(imagem_x, imagem_y);
            })

            $("#addMesa").click(() => {
                addMesa(0,0,0);
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

            // $(".editConfig").change(function (e){
            //     let id = e.target.value;
            //     editConfig(id);
            //
            // })

            $('.panel-collapse').on('show.bs.collapse', function () {
                $(this).siblings('.panel-heading').addClass('active');
            });

            $('.panel-collapse').on('hide.bs.collapse', function () {
                $(this).siblings('.panel-heading').removeClass('active');
            });

            getMesasConfig();
            getMesas();
        } );

        function getOnlyMesas(){
            fetch('manutencao/mesa/listar')
                .then(res => res.json())
                .then(data => {
                    mesas = data.mesas;
                    proximaNumero = data.proxima;
                    $(".drag-drop").text(proximaNumero);
                    renderMesas();
                })
        }

        function getMesas(){
            fetch('manutencao/mesa/listar')
                .then(res => res.json())
                .then(data => {
                    mesas = data.mesas;
                    proximaNumero = data.proxima;
                    $(".drag-drop").text(proximaNumero);
                    renderMesas();
                    renderMapaMesas();
                })
        }

        function getMapaMesas(){
            fetch('manutencao/mesa/listar')
                .then(res => res.json())
                .then(data => {
                    mesas = data.mesas;
                    proximaNumero = data.proxima;
                    $(".drag-drop").text(proximaNumero);
                    renderMapaMesas();
                })
        }

        function getMesasConfig(){
            fetch('{{route('mapademesas.admin.mesa-tipo-config.actives')}}')
                .then(res => res.json())
                .then(data => {
                    mesasConfig = data;
                    renderMesasConfig();
                })
        }

        async function addMesa(top, left, config = 0){

            return fetch(`manutencao/mesa/add/${top}/${left}/${config}`)
                .then(res => res.json())

        }

        function renderMesasConfig(){
            $('#mesas_config').html('');
            mesasConfig.forEach((element) => {
                $("#mesas_config").append(`
                    <div title="${element.nome}" class="drag-drop margin-5" data-codconfig="${element.id}" style="display: inline-block; float: left; border: 2px ${element.color_livre} solid; text-align: center; width: ${element.width}px; height: ${element.height}px; border-radius: ${element.radius}px; line-height: ${element.line_height}px; font-size: ${element.font_size}px; background-color: ${element.background_color_livre}; color: ${element.color_livre}; font-weight: bold;">${proximaNumero}</div>
                `);
            });



            $('#mesas_config').append(`<div style="clear: both;"></div>`);
        }

        function renderMesas(){
            $('.containerMesas').html('');
            mesas.forEach((element) => {
                const config = element.config;

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
                            <div class="col-md-3"><input id="mesa_${element.numero}_y" data-numero="${element.id}" class="form-control mesaTop" type="number" name="top" value="${element.top}"></div>
                            <div class="col-md-3"><input id="mesa_${element.numero}_x" data-numero="${element.id}" class="form-control mesaLeft" type="number" name="left" value="${element.left}"></div>
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
                if(element.status == 1){
                    const config = element.config;

                    let color = config.color_livre;
                    let bgColor = config.background_color_livre;

                    if(element.bloqueada == 1){
                        color = config.color_reversada;
                        bgColor = config.background_color_reversada;
                    }

                    $("#mapa").append(`
                    <div class="draggable" data-numero="${element.numero}" style="position: absolute; cursor: pointer; border: 2px ${config.color_livre} solid; text-align: center; width: ${config.width}px; height: ${config.height}px; border-radius: ${config.radius}px; line-height: ${config.line_height}px; font-size: ${config.font_size}px; background-color: ${bgColor}; top: ${element.top}px; left: ${element.left}px; z-index: 2; color: ${color}; font-weight: bold;">${element.numero}</div>
                `);
                }


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

        function editTopLeft(id, top, left){
            fetch(`manutencao/mesa/edit-top-left/${id}/${top}/${left}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        //getMapaMesas();
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
                        getMesas();
                    }
                })
        }

        function editarConfig(){
            const idConfig = $('.editConfig').val();

            fetch(`/mapademesas/admin/mesa-tipo-config/${idConfig}`)
                .then(res => res.json())
                .then(async (data) => {
                    if(data.id){
                        console.log(data);

                        const {value: formValues}  = await Swal.fire({
                            title: 'Editar Configuração',
                            width: 700,
                            html:
                                '<hr>' +
                                '<div class="col-md-4 form-group"><label>LARGURA</label><input id="swal2-input2" type="number" class="form-control"></div>' +
                                '<div class="col-md-4 form-group"><label>ALTURA</label><input id="swal2-input3" type="number" class="form-control"></div>' +
                                '<div class="col-md-4 form-group"><label>ARREDONDAMENTO</label><input id="swal2-input4" type="number" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>ALTURA DA LINHA</label><input id="swal2-input5" type="number" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>TAMANHO DO TEXTO</label><input id="swal2-input6" type="number" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DE FUNDO LIVRE</label><input id="swal2-input7" style="padding: 2px;" type="color" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DO TEXTO LIVRE</label><input id="swal2-input8" style="padding: 2px;" type="color" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DO FUNDO OCUPADO</label><input id="swal2-input9" style="padding: 2px;" type="color" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DO TEXTO OCUPADO</label><input id="swal2-input10" style="padding: 2px;" type="color" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DO FUNDO BLOQUEADO</label><input id="swal2-input11" style="padding: 2px;" type="color" class="form-control"></div>' +
                                '<div class="col-md-6 form-group"><label>COR DO TEXTO BLOQUEADO</label><input id="swal2-input12" style="padding: 2px;" type="color" class="form-control"></div>',
                            focusConfirm: false,
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Editar',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return [
                                    document.getElementById('swal2-input2').value,
                                    document.getElementById('swal2-input3').value,
                                    document.getElementById('swal2-input4').value,
                                    document.getElementById('swal2-input5').value,
                                    document.getElementById('swal2-input6').value,
                                    document.getElementById('swal2-input7').value,
                                    document.getElementById('swal2-input8').value,
                                    document.getElementById('swal2-input9').value,
                                    document.getElementById('swal2-input10').value,
                                    document.getElementById('swal2-input11').value,
                                    document.getElementById('swal2-input12').value
                                ]
                            },
                            onOpen: () => {
                                document.getElementById('swal2-input2').value = data.width;
                                document.getElementById('swal2-input3').value = data.height;
                                document.getElementById('swal2-input4').value = data.radius;
                                document.getElementById('swal2-input5').value = data.line_height;
                                document.getElementById('swal2-input6').value = data.font_size;
                                document.getElementById('swal2-input7').value = data.background_color_livre;
                                document.getElementById('swal2-input8').value = data.color_livre;
                                document.getElementById('swal2-input9').value = data.background_color_ocupada;
                                document.getElementById('swal2-input10').value = data.color_ocupada;
                                document.getElementById('swal2-input11').value = data.background_color_reversada;
                                document.getElementById('swal2-input12').value = data.color_reversada;
                                return true;
                            }
                        })

                        if (formValues) {

                            const formBody = `
                                    &width=${formValues[0]}
                                    &height=${formValues[1]}
                                    &radius=${formValues[2]}
                                    &line_height=${formValues[3]}
                                    &font_size=${formValues[4]}
                                    &background_color_livre=${formValues[5]}
                                    &color_livre=${formValues[6]}
                                    &background_color_ocupada=${formValues[7]}
                                    &color_ocupada=${formValues[8]}
                                    &background_color_reversada=${formValues[9]}
                                    &color_reversada=${formValues[10]}
                                    `;

                            fetch(`/mapademesas/admin/mesa-tipo-config/${idConfig}`, {
                                method: 'PUT',
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded",
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                body: formBody
                            }).then(function(response) {
                                return response.json();
                            }).then(function(data) {
                                if(data){
                                    getMesas();
                                    getMapaMesas();
                                    getMesasConfig();
                                }
                            });

                        }

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

        function draggable(){
            //draggable
            interact('.draggable')
                .draggable({
                    // enable inertial throwing
                    inertia: true,
                    // keep the element within the area of it's parent
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: 'parent',
                            endOnly: true
                        })
                    ],
                    // enable autoScroll
                    autoScroll: true,
                    onmove: function (event){

                        let mesaNumero = $(event.currentTarget).data('numero');
                        let x = (event.currentTarget.offsetLeft + parseInt(event.currentTarget.getAttribute('data-x')));
                        let y = (event.currentTarget.offsetTop + parseInt(event.currentTarget.getAttribute('data-y')));
                        //console.log(mesaNumero, x, y);
                        $(`#mesa_${mesaNumero}_x`).val(x);
                        $(`#mesa_${mesaNumero}_y`).val(y);

                    },

                    listeners: {
                        // call this function on every dragmove event
                        move: dragMoveListener,

                        // call this function on every dragend event
                        end (event) {
                            let x = (event.currentTarget.offsetLeft + parseInt(event.currentTarget.getAttribute('data-x')));
                            let y = (event.currentTarget.offsetTop + parseInt(event.currentTarget.getAttribute('data-y')));

                            let mesaNumero = $(event.currentTarget).data('numero');

                            let id_x = $($(`#mesa_${mesaNumero}_x`)).data('numero');
                            let id_y = $($(`#mesa_${mesaNumero}_y`)).data('numero');

                            //editLeft(id_x, x);
                            //editTop(id_y, y);
                            editTopLeft(id_x, y, x);

                        }
                    }
                })


        }

        function dragMoveListener (event) {
            var target = event.target
            // keep the dragged position in the data-x/data-y attributes
            var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
            var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

            // translate the element
            target.style.webkitTransform =
                target.style.transform =
                    'translate(' + x + 'px, ' + y + 'px)'

            // update the posiion attributes
            target.setAttribute('data-x', x)
            target.setAttribute('data-y', y)
        }

        // this function is used later in the resizing and gesture demos
        window.dragMoveListener = dragMoveListener

        draggable();


        // enable draggables to be dropped into this
        interact('.dropzone').dropzone({
            // only accept elements matching this CSS selector
            accept: '.drag-drop',
            // Require a 75% element overlap for a drop to be possible
            overlap: 0.75,

            // listen for drop related events:

            ondropactivate: function (event) {
                // add active dropzone feedback
                event.target.classList.add('drop-active')
            },
            ondragenter: function (event) {

                var draggableElement = event.relatedTarget
                var dropzoneElement = event.target

                // feedback the possibility of a drop
                dropzoneElement.classList.add('drop-target')
                draggableElement.classList.add('can-drop')
                //draggableElement.textContent = 'Dragged in'
            },
            ondragleave: function (event) {
                // remove the drop feedback style
                event.target.classList.remove('drop-target')
                event.relatedTarget.classList.remove('can-drop')
                //event.relatedTarget.textContent = 'Dragged out'
            },
            ondrop: async function (event) {

                let x = (event.currentTarget.offsetLeft + parseInt(event.currentTarget.getAttribute('data-x')));
                let y = (event.currentTarget.offsetTop + parseInt(event.currentTarget.getAttribute('data-y')));

                var offset = $(event.target).offset();
                var offsetlimiteMove = $(event.relatedTarget).offset();
                var totalLeft = offset.left-offsetlimiteMove.left;
                var totalTop = offset.top-offsetlimiteMove.top;
                const codconfig = $(event.relatedTarget).data('codconfig');

                const element = await addMesa(parseInt(Math.abs(totalTop)),parseInt(Math.abs(totalLeft,0)), codconfig);
                console.log(element);
                const config = element.config;

                $("#mapa").append(`
                    <div class="draggable" data-numero="${element.numero}" style="position: absolute; border: 2px ${config.color_livre} solid; text-align: center; width: ${config.width}px; height: ${config.height}px; border-radius: ${config.radius}px; line-height: ${config.line_height}px; font-size: ${config.font_size}px; background-color: ${config.background_color_livre}; top: ${element.top}px; left: ${element.left}px; z-index: 2; color: ${config.color_livre}; font-weight: bold;">${element.numero}</div>
                `);
                event.relatedTarget.remove();
                getOnlyMesas();


            },
            ondropdeactivate: function (event) {
                // remove active dropzone feedback
                event.target.classList.remove('drop-active')
                event.target.classList.remove('drop-target')
                setTimeout(() => {
                    event.relatedTarget.remove();
                }, 200);
            }
        })

        interact('.drag-drop')
            .draggable({
                inertia: true,
                modifiers: [
                    interact.modifiers.restrictRect()
                ],
                autoScroll: true,
                listeners: { move: dragMoveListener }
            }).on('move', function (event) {
            var interaction = event.interaction;
            if (interaction.pointerIsDown && !interaction.interacting() && event.currentTarget.getAttribute('clonable') != 'false') {
                var original = event.currentTarget;
                var clone = event.currentTarget.cloneNode(true);
                var x = clone.offsetLeft;
                var y = clone.offsetTop;
                clone.setAttribute('clonable','false');
                clone.style.position = "absolute";
                clone.style.left = original.offsetLeft+"px";
                clone.style.top = original.offsetTop+"px";
                original.parentElement.appendChild(clone);
                interaction.start({ name: 'drag' },event.interactable,clone);
            }
        });
    </script>


@endsection

@section('style')
   <style>
       .panel-heading {
           padding: 0;
           border:0;
       }
       .panel .panel-heading {
           padding: 10px 15px !important;
       }

       .panel-title>a, .panel-title>a:active{
           display:block;
           padding:2px;
           color:#555;
           font-size:16px;
           font-weight:bold;
           text-transform:uppercase;
           letter-spacing:1px;
           word-spacing:3px;
           text-decoration:none;
           cursor: move;
       }
       .panel-heading  a:before {
           font-family: 'Glyphicons Halflings';
           content: "\e114";
           float: right;
           transition: all 0.5s;
       }
       .panel-heading.active a:before {
           -webkit-transform: rotate(180deg);
           -moz-transform: rotate(180deg);
           transform: rotate(180deg);
       }
   </style>
@endsection