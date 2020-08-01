<?php

namespace Modules\MapaDeMesas\Http\Controllers\Admin;


use DataTables\Editor\Format;
use DataTables\Editor\Options;
use DataTables\Editor\Validate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use
    DataTables\Database,
    DataTables\Editor,
    DataTables\Editor\Field;
use Illuminate\Support\Facades\DB;
use Modules\MapaDeMesas\Entities\MesasTipoConfig;
use Modules\MapaDeMesas\Http\Traits\DatatableTrait;

class MesaTipoConfigController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        return view('mapademesas::admin.mesa-tipo-config.index');
    }

    public function actives()
    {
        return MesasTipoConfig::active()->get();
    }

    public function datatable()
    {

        $mapa_id = filter_input(INPUT_GET, 'mapa_id', FILTER_VALIDATE_INT);

        Editor::inst( $this->db, 'mesas_tipo_configs', 'id' )
            ->fields(
                Field::inst( 'mesas_tipo_configs.id' ),

                Field::inst( 'mesas_tipo_configs.nome' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.width' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.height' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.radius' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.line_height' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.font_size' )
                    ->Validator(Validate::numeric())
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.background_color_livre' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.color_livre' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.background_color_ocupada' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.color_ocupada' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.background_color_reversada' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.color_reversada' )
                    ->Validator(Validate::notEmpty()),

                Field::inst( 'mesas_tipo_configs.active' )
                    ->Validator(Validate::boolean())

                /* LEFT JOIN  */

            )
            ->where(function ($q) {
            })
            ->process( $_POST )
            ->debug(true)
            ->json();
    }

    public function show(MesasTipoConfig $mesaTipoConfig)
    {
        return $mesaTipoConfig;
    }

    public function update(MesasTipoConfig $mesaTipoConfig, Request $request)
    {
        $mesaTipoConfig->update($request->all());
        return $mesaTipoConfig;
    }
}
