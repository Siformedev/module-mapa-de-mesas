@extends('gerencial.inc.layout')

@section('content')

    <section class="page-content">
        <div class="page-content-inner">
            <div class="row">
                <section class="panel">
                    <div class="panel-heading" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>MESAS TIPO CONFIG</h3>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">

                                <table id="mesas" class="table table-hover nowrap dataTable dtr-inline">
                                    <thead>
                                    <tr>
{{--                                        <th>#</th>--}}
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Width</th>
                                        <th>Height</th>
                                        <th>Radius</th>
                                        <th>Line Height</th>
                                        <th>Font Size</th>
                                        <th>BG Color Livre</th>
                                        <th>Texto Color Livre</th>
                                        <th>BG Color Ocupada</th>
                                        <th>Texto Color Ocupada</th>
                                        <th>BG Color Bloqueada</th>
                                        <th>Texto Color Bloqueada</th>
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
                    url: '{{route('mapademesas.admin.mesa-tipo-config.datatable')}}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                table: '#mesas',
                fields: [
                    { label: "Nome <font color=red>*</font>", name: "mesas_tipo_configs.nome", attr: {type: 'text'} },
                    { label: "Width <font color=red>*</font>", name: "mesas_tipo_configs.width",  attr: {type: 'number'}},
                    { label: "Height <font color=red>*</font>", name: "mesas_tipo_configs.height",  attr: {type: 'number'} },
                    { label: "Radius <font color=red>*</font>", name: "mesas_tipo_configs.radius",  attr: {type: 'number'} },
                    { label: "line_height <font color=red>*</font>", name: "mesas_tipo_configs.line_height",  attr: {type: 'number'} },
                    { label: "font_size <font color=red>*</font>", name: "mesas_tipo_configs.font_size",  attr: {type: 'number'} },
                    { label: "background_color_livre <font color=red>*</font>", name: "mesas_tipo_configs.background_color_livre",  attr: {type: 'color', style: 'padding: 2px'} },
                    { label: "color_livre <font color=red>*</font>", name: "mesas_tipo_configs.color_livre",  attr: {type: 'color', style: 'padding: 2px'} },
                    { label: "background_color_ocupada <font color=red>*</font>", name: "mesas_tipo_configs.background_color_ocupada",  attr: {type: 'color', style: 'padding: 2px'} },
                    { label: "color_ocupada <font color=red>*</font>", name: "mesas_tipo_configs.color_ocupada",  attr: {type: 'color', style: 'padding: 2px'} },
                    { label: "background_color_reversada <font color=red>*</font>",  name: "mesas_tipo_configs.background_color_reversada", attr: {type: 'color', style: 'padding: 2px'} },
                    { label: "color_reversada <font color=red>*</font>",  name: "mesas_tipo_configs.color_reversada", attr: {type: 'color', style: 'padding: 2px'} },
                    {
                        label: "Status",
                        name: "mesas_tipo_configs.active",
                        type:  "radio",
                        options: [
                            { label: "INATIVO", value: 0 },
                            { label: "ATIVO",  value: 1 }
                        ]
                    },
                ]
            } );

            editor.on('open', function (){
            });

            const table = $('#mesas').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                ajax: {
                    url: '{{route('mapademesas.admin.mesa-tipo-config.datatable')}}',
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
                    { data: "mesas_tipo_configs.id" },
                    { data: "mesas_tipo_configs.nome" },
                    { data: "mesas_tipo_configs.width" },
                    { data: "mesas_tipo_configs.height" },
                    { data: "mesas_tipo_configs.radius" },
                    { data: "mesas_tipo_configs.line_height" },
                    { data: "mesas_tipo_configs.font_size" },
                    { data: "mesas_tipo_configs.background_color_livre" },
                    { data: "mesas_tipo_configs.color_livre" },
                    { data: "mesas_tipo_configs.background_color_ocupada" },
                    { data: "mesas_tipo_configs.color_ocupada" },
                    { data: "mesas_tipo_configs.background_color_reversada" },
                    { data: "mesas_tipo_configs.color_reversada" },
                    { data: null, render: function (row){
                            return (row.mesas_tipo_configs.active) ? 'ATIVO' : 'INATIVO';
                    } }
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
        } );
    </script>

    <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/v/bs-3.3.7/moment-2.18.1/jszip-2.5.0/pdfmake-0.1.36/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/dataTables.editor.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/editor.bootstrap.min.js') }}"></script>

@endsection