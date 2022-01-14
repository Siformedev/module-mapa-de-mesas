@extends('gerencial.inc.layout')

@section('content')

    <section class="page-content">
        <div class="page-content-inner">
            <div class="row">
                <section class="panel">
                    <div class="panel-heading" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>MESAS ESCOLHIDAS</h3>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>FILTROS</h6>
                                <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Mapas</label>
                                    <select class="form-control" name="mapa_id" id="mapa_id">
                                        <option value="0">Todos</option>
                                        @foreach(\Modules\MapaDeMesas\Entities\Mapas::where('status', 1)->get() as $mapa)
                                            <option value="{{$mapa->id}}">{{$mapa->nome}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                </div>
                                <hr>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">

                                <table id="mesas_escolhidas" class="table table-hover nowrap dataTable dtr-inline">
                                    <thead>
                                    <tr>
{{--                                        <th>#</th>--}}
                                        <th>Mapa</th>
                                        <th>Mesa</th>
                                        <th>Evento</th>
                                        <th>Formando</th>
                                        <th>Produto</th>
                                        <th>Cancelado</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">

                    </div>
                </section>
            </div>
        </div>

    </section>

    <script type="text/javascript">
        $(document).ready(function() {

            const edit = {
                mesa_id: null
            };

            const editor = new $.fn.dataTable.Editor( {
                i18n: {
                    create: {
                        button: "Novo",
                        title:  "Criar Novo Registro",
                        submit: "Salvar"
                    },
                    edit: {
                        button: "Editar",
                        title:  "Atualização de Registro",
                        submit: "Salvar atualizações"
                    },
                    remove: {
                        button: "Deletar",
                        title:  "Deletar Registro",
                        submit: "Deletar",
                        confirm: {
                            _: "Você tem certeza que deseja excluir %d ?",
                            1: "Você tem certeza que deseja excluir ?"
                        }
                    },
                    error: {
                        system: "Houveram erros, por favor, tente novamente em alguns instantes."
                    },
                    multi: {
                        title: "Multiplos Valores",
                        info: "Os registros selecionados possuem valores distintos, por isso alguns estão desabilitados",
                        restore: "Anular modificações"
                    },
                    datetime: {
                        previous: 'Anterior',
                        next:     'Próximo',
                        months:   [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
                        weekdays: [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab' ]
                    }
                },
                ajax: {
                    url: '{{route('mapademesas.admin.mesas-escolhidas.datatable')}}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                table: '#mesas_escolhidas',
                fields: [
                    { label: "Mapa <font color=red>*</font>", name: "mesa_escolhidas.mapa_id", type: "select", placeholder: "Selecione..." },
                    { label: "Mesa Número <font color=red>*</font>", name: "mesa_escolhidas.mesa_id", type: "select" },
                    { label: "Formando <font color=red>*</font>", name: "mesa_escolhidas.forming_id", type: "select", placeholder: "Selecione..." },
                    { label: "Produto <font color=red>*</font>", name: "mesa_escolhidas.fps_id", type: "select", placeholder: "Selecione..." },
                    {
                        label: "Cancelado?",
                        name: "mesa_escolhidas.cancelado",
                        type:  "radio",
                        options: [
                            { label: "NÃO", value: 0 },
                            { label: "SIM",  value: 1 }
                        ]
                    }
                ]
            } );

            const table = $('#mesas_escolhidas').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                ajax: {
                    url: '{{route('mapademesas.admin.mesas-escolhidas.datatable')}}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                select: true,
                processing : true,
                responsive: true,
                lengthChange: true,
                stateSave: true,
                fixedHeader: true,
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    "select": {
                        "rows": {
                            "_": "Selecionado %d linhas",
                            "0": "Nenhuma linha selecionada",
                            "1": "Selecionado 1 linha"
                        }
                    },
                    "buttons": {
                        "copy": "Copiar para a área de transferência",
                        "copyTitle": "Cópia bem sucedida",
                        "copySuccess": {
                            "1": "Uma linha copiada com sucesso",
                            "_": "%d linhas copiadas com sucesso"
                        },
                        "colvis": 'Visualizar Colunas'
                    }
                },
                columns: [
                    // { data: null, render: function (row){
                    //         return `<a href="mapa/${row.mapas.id}/manutencao" class="btn btn-info btn-m"><i class="glyphicon glyphicon-wrench"></i></a>`;
                    // } },
                    { data: "mapas.nome" },
                    { data: "mesas.numero" },
                    { data: "events.name" },
                    { data: null, render: function(row){
                        return row.formings.nome + ' ' + row.formings.sobrenome;
                    }},
                    { data: "formando_produtos_e_servicos.name" },
                    { data: null, render: function ( val, type, row ) {
                            return row.mesa_escolhidas.cancelado ? 'SIM' : 'NÃO';
                        }
                    }
                ],
                buttons: [
                    { extend: "create", editor: editor },
                    { extend: "edit",   editor: editor },
                    { extend: "remove", editor: editor },
                    {
                        extend: 'collection',
                        text: 'Export',
                        buttons: [
                            'copy',
                            'excel',
                            'csv',
                            'print'
                        ]
                    }
                ]
            });

            table.buttons()
                .container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

            $("#mapa_id").change(e => {
                const id = e.target.value;
                table.ajax.url('{{route('mapademesas.admin.mesas-escolhidas.datatable')}}?mapa_id=' + id).load();
            })

            editor.on('open', function (e, m, t){
                $(editor.field('mesa_escolhidas.forming_id').input()).addClass('forming_id')
                $('.forming_id').select2();
            });

            $('#mesas_escolhidas tbody').on( 'click', 'tr', async function (e) {
                const rs = table.row( this ).data();
                if(!$(e.currentTarget).hasClass('selected')){
                    if( rs.mesa_escolhidas.mesa_id ){
                        edit.mesa_id = rs.mesa_escolhidas.mesa_id;
                    }
                }
            } );

            $( editor.field( 'mesa_escolhidas.mapa_id' ).input() ).on( 'change', function () {

                const mapa_id = editor.field( 'mesa_escolhidas.mapa_id' ).val();
                if(mapa_id > 0){
                    getMesas(editor, mapa_id, edit.mesa_id);
                }


            } );

            $( editor.field( 'mesa_escolhidas.forming_id' ).input() ).on( 'change', function () {

                const forming_id = editor.field( 'mesa_escolhidas.forming_id' ).val();
                const mapa_id = editor.field( 'mesa_escolhidas.mapa_id' ).val();
                if(forming_id > 0){
                    getProdutos(editor, mapa_id, forming_id);
                }


            } );
        } );

        function getMesas(editor, mapa_id, mesa_id = 0){
            fetch(`mesas-escolhidas/mapa/${mapa_id}/mesas`)
                .then(res => res.json())
                .then(data => {
                    if(data){
                        var option = {};
                        var optionsA = [];

                        option.label = "Selecione...";
                        option.value = "";
                        optionsA.push(option);

                        data.forEach(function(mesa){
                                option = {};
                                option.label = mesa.numero;
                                option.value = mesa.id;
                                optionsA.push(option);

                        });
                        editor.field('mesa_escolhidas.mesa_id').update(optionsA);
                    }
                })
        }

        function getProdutos(editor, mapa_id, forming_id){
            fetch(`mesas-escolhidas/mapa/${mapa_id}/forming/${forming_id}`)
                .then(res => res.json())
                .then(data => {
                    if(data){
                        var option = {};
                        var optionsB = [];

                        option.label = "Selecione...";
                        option.value = "";
                        optionsB.push(option);

                        data.forEach(function(fps){
                            option = {};
                            option.label = fps.name;
                            option.value = fps.id;
                            optionsB.push(option);

                        });
                        console.log(optionsB);
                        editor.field('mesa_escolhidas.fps_id').update(optionsB);
                    }
                })
        }
    </script>

    <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/v/bs-3.3.7/moment-2.18.1/jszip-2.5.0/pdfmake-0.1.36/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/dataTables.editor.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/editor.bootstrap.min.js') }}"></script>

@endsection