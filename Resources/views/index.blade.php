@extends('gerencial.inc.layout')

@section('content')
    <section class="page-content">
        <div class="page-content-inner">
            <section class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-2"><img class="img-responsive img-thumbnail img-circle img-prod" style="width: 150px; height: 150px;" src="https://cdn0.iconfinder.com/data/icons/e-commerce-and-shopping-2/512/contract_document_agreement_paper_sign_list_signature_treaty_business_securities_certificate_text_page_term_pact_sheet_flat_design_icon-512.png"></div>
                        <div class="col-md-5">
                            <h3>Contrato da Comissão</h3>
                            <h5>Realizar o Download do Contrato</h5>
                        </div>
                        <div class="col-md-5">
                            <span>

                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <hr>
                        <div class="col-md-12">
                            <b>Descrição:</b> <br>
                            Abaixo você pode realizar o download do contrato da turma.
                        </div>
                    </div>

                    <hr>


                    <!--<h3>Dados para Compra e Pagamento</h3>-->
                    {!! Form::open(['route' => 'comissao.contrato']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-block">Baixar Contrato</button>
                        </div>

                        {{ Form::hidden('downcontract', 'ok') }}
                    </div>
                    <hr>
                    {!! Form::close() !!}


                </div>
                <div class="panel-footer">

                </div>
            </section>
        </div>

    </section>

@endsection