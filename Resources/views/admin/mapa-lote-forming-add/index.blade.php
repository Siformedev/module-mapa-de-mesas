@extends('gerencial.inc.layout')

@section('content')

    <section class="page-content">
        <div class="page-content-inner">
            <div class="row">
                <section class="panel">
                    <div class="panel-heading" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>MAPA LOTE FORMANDO</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>FILTROS</h6>
                                <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Contratos</label>
                                    <select class="form-control" name="contract_id" id="contract_id">
                                        <option value="" disabled selected>Selecione...</option>
                                        @foreach(\App\Contract::all() as $contract)
                                            <option value="{{$contract->id}}">{{$contract->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Mapas</label>
                                    <select class="form-control" name="mapa_id" id="mapa_id">
                                        <option value="0">Selecione o Contrato no campo anterior</option>
                                    </select>

                                </div>
                                </div>
                                <hr>
                            </div>

                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">

                                <table id="mapa-lote-forming-add" class="table table-hover nowrap dataTable dtr-inline">
                                    <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>Formando</th>
                                        <th>Curso</th>
                                        <th>Data de Adesão</th>
                                        <th>Quantidade</th>
                                        <th>Lote</th>
                                        <th>Data Inicio</th>
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
        let idContract;
        let idMapa;
        let Ids = [];

        $(function (){


            var table = $("#mapa-lote-forming-add").DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                ajax: "add/list",
                lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
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
                columnDefs: [{
                    targets: 0,
                    searchable:false,
                    orderable:false,
                    width:'1%',
                    className: 'dt-body-center',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                }],

                select: {
                    selected: null,
                    style:    'multi',
                    selector: 'td:first-child input'
                },
                columns: [
                    { data: null },
                    { data: null, render: function (row){
                        return row.forming.nome + ' ' + row.forming.sobrenome;
                        } },
                    { data: "forming.course.name" },
                    { data: "forming.created_at" },
                    { data: "quantity" },
                    { data: "lote.numero" },
                    { data: "lote.data_inicio" }
                ],
                buttons: [
                    {
                        text: 'Atribuir Lote',
                        action: async function ( e, dt, node, config ) {

                            if(!Ids.length){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Nenhum formando selecionado!'
                                })
                                return;
                            }

                            const { value: formValues } = await Swal.fire({
                                title: 'Adicionar/Editar Lotes',
                                html:
                                    '<div class="form-group"><label>LOTE</label><input required min="1" type="number" id="swal-input1" class="form-control"></div>' +
                                    '<div class="form-group"><label>DATA INICIO</label><input required type="datetime-local" id="swal-input2" class="form-control"></div>',
                                focusConfirm: false,
                                preConfirm: () => {
                                    return [
                                        document.getElementById('swal-input1').value,
                                        document.getElementById('swal-input2').value
                                    ]
                                }
                            })

                            if (formValues) {

                                if(parseInt(formValues[0]) <= 0 || formValues[1] == ''){
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Todos os campos são obrigatórios!'
                                    })
                                    return;
                                }

                                const formBody = `lote=${formValues[0]}
                                    &data_inicio=${formValues[1]}
                                    &ids=${Ids.join(`,`)}`;

                                fetch(`contract/${idContract}/mapa/${idMapa}`,{
                                    method: 'POST',
                                    body: formBody,
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded",
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        if(data.success) reloadDataTable();
                                    })


                            }
                        }
                    },
                    {
                        text: 'Remover Lote',
                        action: async function ( e, dt, node, config ) {

                            if(!Ids.length){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Nenhum formando selecionado!'
                                })
                                return;
                            }

                            Swal.fire({
                                title: 'Você tem certeza que deseja apagar remover esses lotes?',
                                text: "Essa ação não podera ser desfeita",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sim',
                                cancelButtonText: 'Não'
                            }).then((result) => {
                                if (result.value) {

                                    const formBody = `ids=${Ids.join(`,`)}`;

                                    fetch(`contract/${idContract}/mapa/${idMapa}/remove`,{
                                        method: 'DELETE',
                                        body: formBody,
                                        headers: {
                                            "Content-Type": "application/x-www-form-urlencoded",
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    })
                                        .then(res => res.json())
                                        .then(data => {
                                            if(data.success) reloadDataTable();
                                        })

                                }
                            })
                        }
                    }
                ]
            });

            $('#mapa-lote-forming-add tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');

                var data = table.row($row).data();

                if(e.currentTarget.checked){
                    Ids.push(data.forming.id);
                    console.log(Ids);
                }else{
                    Ids.splice(Ids.indexOf(data.forming.id), 1);
                    console.log(Ids);
                }
            });

            $("#contract_id").change(function (e){
                idContract = e.target.value;
                fetch(`contract/${idContract}/find/mapas`)
                .then(res => res.json())
                .then(data => {
                    if(data){
                        $("#mapa_id").html('');
                        $("#mapa_id").append(`<option value="" selected disabled>Selecione....</option>`);
                        data.forEach(function (mapa){
                            $("#mapa_id").append(`<option value="${mapa.id}">${mapa.nome}</option>`);
                        })
                    }
                })
            });

            $("#mapa_id").change(function (e){
                idMapa = e.target.value;
                if(idContract > 0 && idMapa > 0){
                    reloadDataTable();
                }
            });

            function reloadDataTable(){
                table.ajax.url(`add/list?contract=${idContract}&mapa=${idMapa}`).load()
                Ids = [];
            }


        });
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/v/bs-3.3.7/moment-2.18.1/jszip-2.5.0/pdfmake-0.1.36/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/dataTables.editor.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatable-editor/js/editor.bootstrap.min.js') }}"></script>

@endsection