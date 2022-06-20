@extends('gerencial.inc.layout')

@section('content')

    <section class="page-content">
        <div class="page-content-inner">
            <div class="row">
                <section class="panel">
                    <div class="panel-heading" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>MAPAS</h3>
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
                                    <label>Contrato</label>
                                    <select class="form-control" name="contract_id" id="contract_id">
                                        <option value="0">Todos</option>
                                        @foreach(\App\Contract::all() as $contrato)
                                            <option value="{{$contrato->id}}">{{$contrato->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                </div>
                                <hr>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">

                                <table id="example" class="table table-hover nowrap dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Evento</th>
                                        <th>Data Inicio</th>
                                        <th>Data Fim</th>
                                        <th>Status</th>
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
                    url: '{{route('mapademesas.admin.mapa.datatable')}}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                table: '#example',
                fields: [
                    { label: "Nome <font color=red>*</font>", name: "mapas.nome" },
                    { label: "Evento <font color=red>*</font>", name: "mapas.event_id", type: "select" },
                    { label: "Data Início Liberação <font color=red>*</font>", name: "mapas.data_inicio", type: 'datetime', format: 'DD/MM/YYYY H:mm', },
                    { label: "Data Fim Liberação <font color=red>*</font>", name: "mapas.data_fim", type: 'datetime', format: 'DD/MM/YYYY H:mm', },
                    {
                        label: "Status",
                        name: "mapas.status",
                        type:  "radio",
                        options: [
                            { label: "INATIVO", value: 0 },
                            { label: "ATIVO",  value: 1 }
                        ]
                    }
                ]
            } );

            const table = $('#example').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                ajax: {
                    url: '{{route('mapademesas.admin.mapa.datatable')}}',
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
                    { data: null, render: function (row){
                            return `<a href="mapa/${row.mapas.id}/manutencao" class="btn btn-info btn-m"><i class="glyphicon glyphicon-wrench"></i></a>`;
                    } },
                    { data: "mapas.nome" },
                    { data: "events.name" },
                    { data: "mapas.data_inicio" },
                    { data: "mapas.data_fim" },
                    { data: null, render: function ( val, type, row ) {
                            return row.mapas.status ? 'ATIVO' : 'INATIVO';
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

            $("#contract_id").change(e => {
                const id = e.target.value;
                table.ajax.url('{{route('mapademesas.admin.mapa.datatable')}}?contract_id=' + id).load();
            })
        } );
    </script>

    <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/v/bs-3.3.7/moment-2.18.1/jszip-2.5.0/pdfmake-0.1.36/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/dataTables.editor.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/editor.bootstrap.min.js') }}"></script>

@endsection